<?php

namespace App\Tests;

use App\Mail\ForgotPasswordMail;
use App\Models\User;
use App\Tests\Support\AuthTestTrait;
use Illuminate\Console\Events\ScheduledTaskFinished;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;

class AuthTest extends TestCase
{
    use AuthTestTrait;

    protected $admin;
    protected $users;

    public function setUp(): void
    {
        parent::setUp();

        $this->users = $this->getJsonFixture('users.json');
        $this->admin = User::find(1);
    }

    public function testLogin()
    {
        $response = $this->json('post', '/login', [
            'email' => $this->users[1]['email'],
            'password' => $this->users[1]['password'],
        ]);

        $response->assertOk();

        $this->assertArrayHasKey('token', $response->json());
        $response->assertCookie('token');
    }

    public function testLoginWrongCredentials()
    {
        $response = $this->json('post', '/login', [
            'email' => 'wrong email',
            'password' => 'wrong password',
        ]);

        $response->assertUnauthorized();
    }

    public function testLoginWithRemember()
    {
        $response = $this->json('post', '/login', [
            'email' => $this->users[1]['email'],
            'password' => $this->users[1]['password'],
            'remember' => true,
        ]);

        $response->assertOk();
        $response->assertCookie('token');
        $response->assertCookieNotExpired('token');
    }

    public function testLoginWithoutRemember()
    {
        $response = $this->json('post', '/login', [
            'email' => $this->users[1]['email'],
            'password' => $this->users[1]['password'],
            'remember' => false,
        ]);

        $response->assertOk();
        $response->assertCookie('token');
        $this->assertEquals(0, $response->getCookie('token', false)->getExpiresTime());
    }

    public function testLoginAsRegisteredUser()
    {
        $response = $this->json('post', '/login', [
            'email' => $this->users[0]['email'],
            'password' => $this->users[0]['password'],
        ]);

        $response->assertOk();

        $this->assertArrayHasKey('token', $response->json());
        $response->assertCookie('token');
    }

    public function testLoginAsAuthorizedUser()
    {
        $response = $this->actingAs($this->admin)->json('post', '/login', [
            'email' => $this->users[0]['email'],
            'password' => $this->users[0]['password'],
        ]);

        $response->assertOk();
    }

    public function testRegisterAuthorizedUser()
    {
        $data = $this->getJsonFixture('new_user.json');

        $response = $this->actingAs($this->admin)->json('post', '/register', $data);

        $response->assertOk();
    }

    public function testRegisterFromGuestUser()
    {
        $data = $this->getJsonFixture('new_user.json');

        $response = $this->json('post', '/register', $data);

        $response->assertOk();

        $this->assertArrayHasKey('token', $response->json());
        $response->assertCookie('token');
        $this->assertEquals(0, $response->getCookie('token', false)->getExpiresTime());

        $this->assertDatabaseHas('users', $response->json('user'));
        $this->assertDatabaseHas('users', Arr::only($data, ['email', 'name']));
    }

    public function testRegisterFromGuestUserWithRemember()
    {
        $data = $this->getJsonFixture('new_user.json');

        $response = $this->json('post', '/register', array_merge(
            $data,
            ['remember' => true]
        ));

        $response->assertOk();
        $response->assertCookie('token');
        $response->assertCookieNotExpired('token');
    }

    public function testRefreshToken()
    {
        $request = $this->actingAs($this->admin);

        $this->travel(config('jwt.ttl') + 1)->minutes();

        $response = $request->json('get', '/auth/refresh');

        $response->assertOk();

        $response->assertJson(fn (AssertableJson $json) => $json->hasAll(['token', 'ttl', 'refresh_ttl']));

        $this->assertNotEmpty(
            $response->headers->get('authorization')
        );

        $authHeader = $response->headers->get('authorization');
        $explodedHeader = explode(' ', $authHeader);

        $this->assertNotEquals($this->token, last($explodedHeader));

        $response->assertCookie('token');
        $this->assertEquals(0, $response->getCookie('token', false)->getExpiresTime());

        $authCookie = $response->headers->get('cookie');
        $explodedCookie = explode('=', $authCookie);

        $this->assertNotEquals($this->token, last($explodedCookie));
    }

    public function testRefreshTokenAfterRefreshTTL()
    {
        $request = $this->actingAs($this->admin);

        $this->travel(config('jwt.refresh_ttl') + 1)->minutes();

        $response = $request->json('get', '/auth/refresh');

        $response->assertUnauthorized();
    }

    public function testRefreshTokenWithRemember()
    {
        $response = $this->actingAs($this->admin)->json('get', '/auth/refresh', [
            'remember' => true,
        ]);

        $response->assertOk();

        $response->assertCookie('token');
        $response->assertCookieNotExpired('token');
    }

    public function testRefreshTokenWithRememberWithoutBlacklist()
    {
        config(['jwt.blacklist_enabled' => false]);

        $response = $this->actingAs($this->admin)->json('get', '/auth/refresh', [
            'remember' => true,
        ]);

        $response->assertUnauthorized();
    }

    public function testRefreshTokenIat()
    {
        $request = $this->actingAs($this->admin);

        $this->travel(1)->second();

        $response = $request->json('get', '/auth/refresh');

        $authHeader = $response->headers->get('authorization');
        list(, $newToken) = explode(' ', $authHeader);

        $this->assertNotEquals(
            $this->decodeJWTToken($this->token)->iat,
            $this->decodeJWTToken($newToken)->iat
        );
    }

    public function testLogout()
    {
        $response = $this->actingAs($this->admin)->json('post', '/auth/logout');

        $response->assertNoContent();

        $response->assertCookieExpired('token');
    }

    public function testLogoutWithoutBlacklist()
    {
        config(['jwt.blacklist_enabled' => false]);

        $response = $this->actingAs($this->admin)->json('post', '/auth/logout');

        $response->assertUnauthorized();
    }

    public function testForgotPassword()
    {
        Mail::fake();

        $this->mockOpensslRandomPseudoBytes();

        $response = $this->json('post', '/auth/forgot-password', [
            'email' => 'fidel.kutch@example.com',
        ]);

        $response->assertNoContent();

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'fidel.kutch@example.com',
        ]);

        $this->assertMailEquals(ForgotPasswordMail::class, [
            $this->mockedMail('fidel.kutch@example.com', 'forgot_password_email.html', 'Forgot password?'),
        ]);
    }

    public function testForgotPasswordUserDoesNotExists()
    {
        $response = $this->json('post', '/auth/forgot-password', [
            'email' => 'not_exists@example.com',
        ]);

        $response->assertUnprocessable();
    }

    public function testRestorePassword()
    {
        $this->mockBcryptHasher();

        $response = $this->json('post', '/auth/restore-password', [
            'email' => 'fidel.kutch@example.com',
            'password' => 'new_password',
            'token' => '$2y$12$NPNGKmmgS1Fonxmu2UM5nODobyjAiSO2uEt9CEoMRcOXunVUQr1Bq',
            //'token' => '$2y$12$tZyxJSv7BzJ493ChpMTPWeHyC2kg9D5GyrGfKoT.4Nuwil.X5.k4e',
        ]);

        $response->assertNoContent();

        $this->assertDatabaseHas('users', [
            'email' => 'fidel.kutch@example.com',
            'password' => '$2y$12$p9Bub8AaSl7EHfoGMgaXReK7Cs50kjHswxzNPTB5B4mcoRWfHnv8u',
        ]);

        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => 'fidel.kutch@example.com',
        ]);
    }

    public function testRestorePasswordWrongToken()
    {
        $response = $this->json('post', '/auth/restore-password', [
            'email' => 'fidel.kutch@example.com',
            'password' => 'new_password',
            'token' => '$2y$12$iqRo8zSwF7p3hZ6/KWUusuRVausbgVOGHmfhfqo3id.Pa/1IIdL2y',
        ]);

        $response->assertUnprocessable();
    }

    public function testCheckRestoreToken()
    {
        $response = $this->json('post', '/auth/token/check', [
            'token' => 'restore_token',
        ]);

        $response->assertNoContent();
    }

    public function testCheckRestoreWrongToken()
    {
        $response = $this->json('post', '/auth/token/check', [
            'token' => 'wrong_token',
        ]);

        $response->assertUnprocessable();
    }
}

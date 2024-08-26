<?php

namespace App\Tests;

use App\Mail\ForgotPasswordMail;
use App\Models\User;
use App\Tests\Support\AuthTestTrait;
use Illuminate\Support\Facades\Mail;
use Illuminate\Testing\Fluent\AssertableJson;
use RonasIT\Support\Tests\ModelTestState;

class AuthTest extends TestCase
{
    use AuthTestTrait;

    protected static User $admin;
    protected static array $users;

    protected static ModelTestState $userState;

    public function setUp(): void
    {
        parent::setUp();

        self::$users ??= $this->getJsonFixture('users.json');
        self::$admin ??= User::find(1);

        self::$userState ??= new ModelTestState(User::class);
    }

    public function testLogin()
    {
        $response = $this->json('post', '/login', [
            'email' => self::$users[1]['email'],
            'password' => self::$users[1]['password'],
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
            'email' => self::$users[1]['email'],
            'password' => self::$users[1]['password'],
            'remember' => true,
        ]);

        $response->assertOk();
        $response->assertCookie('token');
        $response->assertCookieNotExpired('token');
    }

    public function testLoginWithoutRemember()
    {
        $response = $this->json('post', '/login', [
            'email' => self::$users[1]['email'],
            'password' => self::$users[1]['password'],
            'remember' => false,
        ]);

        $response->assertOk();
        $response->assertCookie('token');
        $this->assertEquals(0, $response->getCookie('token', false)->getExpiresTime());
    }

    public function testLoginAsRegisteredUser()
    {
        $response = $this->json('post', '/login', [
            'email' => self::$users[0]['email'],
            'password' => self::$users[0]['password'],
        ]);

        $response->assertOk();

        $this->assertArrayHasKey('token', $response->json());
        $response->assertCookie('token');
    }

    public function testLoginAsAuthorizedUser()
    {
        $response = $this->actingAs(self::$admin)->json('post', '/login', [
            'email' => self::$users[0]['email'],
            'password' => self::$users[0]['password'],
        ]);

        $response->assertOk();
    }

    public function testRegisterAuthorizedUser()
    {
        $this->mockBcryptHasher();

        $data = $this->getJsonFixture('new_user.json');

        $response = $this->actingAs(self::$admin)->json('post', '/register', $data);

        $response->assertOk();

        self::$userState->assertChangesEqualsFixture('register_authorized_user_users_state.json');
    }

    public function testRegisterFromGuestUser()
    {
        $this->mockBcryptHasher();

        $data = $this->getJsonFixture('new_user.json');

        $response = $this->json('post', '/register', $data);

        $response->assertOk();

        $this->assertArrayHasKey('token', $response->json());
        $response->assertCookie('token');
        $this->assertEquals(0, $response->getCookie('token', false)->getExpiresTime());

        self::$userState->assertChangesEqualsFixture('register_from_guest_user_users_state.json');
    }

    public function testRegisterFromGuestUserWithRemember()
    {
        $data = $this->getJsonFixture('new_user.json');

        $response = $this->json('post', '/register', [
            ...$data,
            'remember' => true,
        ]);

        $response->assertOk();
        $response->assertCookie('token');
        $response->assertCookieNotExpired('token');
    }

    public function testRefreshToken()
    {
        $request = $this->actingAs(self::$admin);

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
        $request = $this->actingAs(self::$admin);

        $this->travel(config('jwt.refresh_ttl') + 1)->minutes();

        $response = $request->json('get', '/auth/refresh');

        $response->assertUnauthorized();
    }

    public function testRefreshTokenWithRemember()
    {
        $response = $this->actingAs(self::$admin)->json('get', '/auth/refresh', [
            'remember' => true,
        ]);

        $response->assertOk();

        $response->assertCookie('token');
        $response->assertCookieNotExpired('token');
    }

    public function testRefreshTokenWithRememberWithoutBlacklist()
    {
        config(['jwt.blacklist_enabled' => false]);

        $response = $this->actingAs(self::$admin)->json('get', '/auth/refresh', [
            'remember' => true,
        ]);

        $response->assertUnauthorized();
    }

    public function testRefreshTokenIat()
    {
        $request = $this->actingAs(self::$admin);

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
        $response = $this->actingAs(self::$admin)->json('post', '/auth/logout');

        $response->assertNoContent();

        $response->assertCookieExpired('token');
    }

    public function testLogoutWithoutBlacklist()
    {
        config(['jwt.blacklist_enabled' => false]);

        $response = $this->actingAs(self::$admin)->json('post', '/auth/logout');

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
            $this->mockedMail(
                emails: 'fidel.kutch@example.com',
                fixture: 'forgot_password_email.html',
                subject: 'Forgot password?',
            ),
        ]);
    }

    public function testForgotPasswordUserDoesNotExists()
    {
        $response = $this->json('post', '/auth/forgot-password', [
            'email' => 'not_exists@example.com',
        ]);

        $response->assertUnprocessable();
    }

    public function testForgotPasswordThrottled()
    {
        $this->mockForgotPasswordThrottled();

        $response = $this->json('post', '/auth/forgot-password', [
            'email' => 'fidel.kutch@example.com',
        ]);

        $response->assertUnprocessable();
    }

    public function testRestorePassword()
    {
        $this->mockBcryptHasher();

        $data = $this->getJsonFixture('restore_password.json');

        $response = $this->json('post', '/auth/restore-password', $data);

        $response->assertNoContent();

        self::$userState->assertChangesEqualsFixture('restore_password_users_state.json');

        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => 'fidel.kutch@example.com',
        ]);
    }

    public function testRestorePasswordWrongToken()
    {
        $data = $this->getJsonFixture('restore_password_wrong_token.json');

        $response = $this->json('post', '/auth/restore-password', $data);

        $response->assertUnprocessable();
    }
}

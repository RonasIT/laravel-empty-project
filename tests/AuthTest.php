<?php

namespace App\Tests;

use App\Mails\ForgotPasswordMail;
use App\Tests\Support\AuthTestTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

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
            'password' => $this->users[1]['password']
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertArrayHasKey('token', $response->json());
        $response->assertCookie('token');
    }

    public function testLoginWrongCredentials()
    {
        $response = $this->json('post', '/login', [
            'email' => 'wrong email',
            'password' => 'wrong password'
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testLoginWithRemember()
    {
        $response = $this->json('post', '/login', [
            'email' => $this->users[1]['email'],
            'password' => $this->users[1]['password'],
            'remember' => true
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertCookie('token');
        $response->assertCookieNotExpired('token');
    }

    public function testLoginWithoutRemember()
    {
        $response = $this->json('post', '/login', [
            'email' => $this->users[1]['email'],
            'password' => $this->users[1]['password'],
            'remember' => false
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertCookie('token');
        $this->assertEquals(0, $response->getCookie('token')->getExpiresTime());
    }

    public function testLoginAsRegisteredUser()
    {
        $response = $this->json('post', '/login', [
            'email' => $this->users[0]['email'],
            'password' => $this->users[0]['password']
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertArrayHasKey('token', $response->json());
        $response->assertCookie('token');
    }

    public function testRegisterFromGuestUser()
    {
        $data = $this->getJsonFixture('new_user.json');

        $response = $this->json('post', '/register', $data);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertArrayHasKey('token', $response->json());
        $response->assertCookie('token');

        $this->assertDatabaseHas('users', $response->json('user'));
        $this->assertDatabaseHas('users', Arr::only($data, ['email', 'name']));
    }

    public function testRefreshToken()
    {
        $response = $this->actingAs($this->admin)->json('get', '/auth/refresh');

        $response->assertStatus(Response::HTTP_OK);

        $this->assertArrayHasKey('token', $response->json());

        $this->assertNotEmpty(
            $response->headers->get('authorization')
        );

        $authHeader = $response->headers->get('authorization');
        $explodedHeader = explode(' ', $authHeader);

        $this->assertNotEquals($this->jwt, last($explodedHeader));

        $response->assertCookie('token');

        $authCookie = $response->headers->get('cookie');
        $explodedCookie = explode('=', $authCookie);

        $this->assertNotEquals($this->jwt, last($explodedCookie));
    }

    public function testLogout()
    {
        $response = $this->actingAs($this->admin)->json('post', '/auth/logout');

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $response->assertCookieExpired('token');
    }

    public function testClearPasswordHash()
    {
        Artisan::call('clear:set-password-hash');

        $usersWithClearedHash = User::whereIn('id', [2, 4, 5])->get()->makeVisible('set_password_hash')->toArray();

        $this->assertEqualsFixture('users_without_set_password_hash.json', $usersWithClearedHash);

        $usersWithSetPasswordHash = User::whereIn('id', [1, 3])->get()->makeVisible('set_password_hash')->toArray();

        $this->assertEqualsFixture('users_with_set_password_hash.json', $usersWithSetPasswordHash);
    }

    public function testForgotPassword()
    {
        Mail::fake();

        $this->mockUniqueTokenGeneration('some_token');

        $response = $this->json('post', '/auth/forgot-password', [
            'email' => 'fidel.kutch@example.com'
        ]);

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('users', [
            'email' => 'fidel.kutch@example.com',
            'set_password_hash' => null,
            'set_password_hash_created_at' => null
        ]);

        Mail::assertQueued(ForgotPasswordMail::class, function ($mail) {
            return $mail->hasTo('fidel.kutch@example.com');
        });
    }

    public function testForgotPasswordUserDoesNotExists()
    {
        $response = $this->json('post', '/auth/forgot-password', [
            'email' => 'not_exists@example.com'
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testRestorePassword()
    {
        $response = $this->json('post', '/auth/restore-password', [
            'password' => 'new_password',
            'token' => 'restore_token',
        ]);

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('users', [
            'email' => 'fidel.kutch@example.com',
            'password' => 'old_password'
        ]);

        $this->assertDatabaseMissing('users', [
            'email' => 'fidel.kutch@example.com',
            'set_password_hash' => 'restore_token'
        ]);
    }

    public function testRestorePasswordWrongToken()
    {
        $response = $this->json('post', '/auth/restore-password', [
            'password' => 'new_password',
            'token' => 'incorrect_token',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testCheckRestoreToken()
    {
        $response = $this->json('post', '/auth/token/check', [
            'token' => 'restore_token',
        ]);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function testCheckRestoreWrongToken()
    {
        $response = $this->json('post', '/auth/token/check', [
            'token' => 'wrong_token',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}

<?php

namespace App\Tests;

use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class AuthTest extends TestCase
{
    protected $admin;
    protected $users;

    public function setUp()
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
    }

    public function testLoginWrongCredentials()
    {
        $response = $this->json('post', '/login', [
            'email' => 'wrong email',
            'password' => 'wrong password'
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testLoginAsRegisteredUser()
    {
        $response = $this->json('post', '/login', [
            'email' => $this->users[0]['email'],
            'password' => $this->users[0]['password']
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertArrayHasKey('token', $response->json());
    }

    public function testRegisterFromGuestUser()
    {
        $data = $this->getJsonFixture('new_user.json');

        $response = $this->json('post', '/register', $data);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testRefreshToken()
    {
        $response = $this->actingAs($this->admin)->json('get', '/auth/refresh');

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertNotEmpty(
            $response->headers->get('authorization')
        );

        $auth = $response->headers->get('authorization');

        $explodedHeader = explode(' ', $auth);

        $this->assertNotEquals($this->jwt, last($explodedHeader));
    }

    public function testForgotPassword()
    {
        $response = $this->json('post', '/auth/forgot-password', [
            'email' => 'fidel.kutch@example.com'
        ]);

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('users', [
            'email' => 'fidel.kutch@example.com',
            'reset_password_hash' => null
        ]);
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
            'reset_password_hash' => 'restore_token'
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
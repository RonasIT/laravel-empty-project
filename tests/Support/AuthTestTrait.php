<?php

namespace App\Tests\Support;

use App\Services\UserService;
use Illuminate\Support\Str;
use RonasIT\Support\Traits\MockTrait;

trait AuthTestTrait
{
    use MockTrait;

    public function mockOpensslRandomPseudoBytes(): void
    {
        config(['app.key' => 'some_app_key']);

        Str::createRandomStringsUsing(fn () => 'value');

        $this->mockNativeFunction('Illuminate\Auth\Passwords', [
            $this->functionCall(
                name: 'hash_hmac',
                arguments: ['sha256', 'value', 'some_app_key'],
                result: 'some_reset_password_token',
            ),
        ]);
    }

    public function mockBcryptHasher(string $password): void
    {
        $this->mockNativeFunction('Illuminate\Hashing', [
            $this->functionCall(
                name: 'password_hash',
                arguments: [$password, PASSWORD_DEFAULT, ['cost' => 12]],
                result: '$2y$12$p9Bub8AaSl7EHfoGMgaXReK7Cs50kjHswxzNPTB5B4mcoRWfHnv7u',
            ),
        ]);
    }

    public function mockForgotPasswordThrottled(string $email): void
    {
        $this->mockClass(UserService::class, [
            [
                'function' => 'forgotPassword',
                'arguments' => [$email],
                'result' => 'passwords.throttled',
            ],
        ]);
    }

    public function decodeJWTToken($token)
    {
        return json_decode(
            base64_decode(
                str_replace(
                    '_',
                    '/',
                    str_replace('-', '+', explode('.', $token)[1]),
                ),
            ),
        );
    }
}

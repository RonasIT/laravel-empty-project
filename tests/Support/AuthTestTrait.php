<?php

namespace App\Tests\Support;

use App\Services\UserService;
use RonasIT\Support\Traits\MockTrait;

trait AuthTestTrait
{
    use MockTrait;

    public function mockOpensslRandomPseudoBytes(string $password): void
    {
        $this->mockNativeFunction('Illuminate\Auth\Passwords', [
            $this->functionCall(
                name: 'hash_hmac',
                arguments: ['sha256', $password, 'b"DÉV&´G"áËa\x1Ae&õš,Ü\x16½\x1A\x15‘\x07CM—ºSVxÅ\x16"'],
                result: '5qw6rdsyd4sa65d4zxfc65ds4fc',
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

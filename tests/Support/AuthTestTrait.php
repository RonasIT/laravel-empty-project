<?php

namespace App\Tests\Support;

use RonasIT\Support\Traits\MockTrait;

trait AuthTestTrait
{
    use MockTrait;

    public function mockOpensslRandomPseudoBytes(): void
    {
        $this->mockNativeFunction('Illuminate\Auth\Passwords', [
            $this->functionCall('hash_hmac', [], '5qw6rdsyd4sa65d4zxfc65ds4fc'),
        ]);
    }

    public function mockBcryptHasher(): void
    {
        /*$this->mockNativeFunction('Illuminate\Hashing', [
            $this->functionCall('password_verify'),
        ]);*/
    }

    public function decodeJWTToken($token)
    {
        return json_decode(
            base64_decode(
                str_replace(
                    '_',
                    '/',
                    str_replace('-', '+', explode('.', $token)[1])
                )
            )
        );
    }
}

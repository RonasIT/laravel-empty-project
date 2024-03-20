<?php

namespace App\Tests\Support;

use RonasIT\Support\Traits\MockTrait;

trait AuthTestTrait
{
    use MockTrait;

    public function mockOpensslRandomPseudoBytes(): void
    {
        $this->mockNativeFunction('App\Services', [
            $this->functionCall('openssl_random_pseudo_bytes', [], '5qw6rdsyd4sa65d4zxfc65ds4fc')
        ]);
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

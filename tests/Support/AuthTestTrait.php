<?php

namespace App\Tests\Support;

use phpmock\phpunit\PHPMock;

trait AuthTestTrait
{
    use PHPMock;

    public function mockOpensslRandomPseudoBytes(): void
    {
        $mock = $this->getFunctionMock('App\Services', 'openssl_random_pseudo_bytes');
        $mock
            ->expects($this->once())
            ->willReturn('5qw6rdsyd4sa65d4zxfc65ds4fc');
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

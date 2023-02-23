<?php

namespace App\Tests\Support;

use App\Services\UserService;
use RonasIT\Support\Traits\MockClassTrait;

trait AuthTestTrait
{
    use MockClassTrait;

    public function mockUniqueTokenGeneration($hash)
    {
        $this->mockClass(UserService::class, [
            ['method' => 'generateHash', 'result' => $hash]
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

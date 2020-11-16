<?php

namespace App\Tests\Support;

use App\Services\UserService;

trait AuthTestTrait
{
    use MockClassTrait;

    public function mockUniqueTokenGeneration($hash)
    {
        $this->mockClass(UserService::class, [
            ['method' => 'generateHash', 'result' => $hash]
        ]);
    }
}
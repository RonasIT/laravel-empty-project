<?php

namespace App\Tests\Support;

use RonasIT\Support\Traits\MockTrait;

trait InitCommandMockTrait
{
    use MockTrait;

    public function mockFilePutContent(...$arguments): void
    {
        $callChain = [
            ['.env.example', $this->getFixture('env.example.yml'), 'optionalParameter', 'optionalParameter'],
            ['.env.development', $this->getFixture('env.development.yml'), 'optionalParameter', 'optionalParameter'],
            ...$arguments,
        ];

        $this->mockNativeFunction(
            namespace: 'App\Console\Commands',
            callChain: array_map(
                fn ($call) => $this->functionCall('file_put_contents', $call),
                $callChain
            )
        );
    }

    public function mockShellExec(): void
    {
        $this->mockNativeFunction('App\Console\Commands', [
            $this->functionCall(
                name: 'shell_exec',
                arguments: ['git ls-remote --get-url origin'],
                result: 'https://github.com/ronasit/laravel-helpers.git'
            ),
        ]);
    }
}

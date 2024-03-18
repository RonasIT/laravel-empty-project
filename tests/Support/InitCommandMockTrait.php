<?php

namespace App\Tests\Support;

use phpmock\phpunit\PHPMock;

trait InitCommandMockTrait
{
    use PHPMock;

    public function mockFilePutContent(...$arguments): void
    {
        $mock = $this->getFunctionMock('App\Console\Commands', 'file_put_contents');

        $callChain = [
            ['.env.example', $this->getFixture('env.example.yml')],
            ['.env.development', $this->getFixture('env.development.yml')],
            ...$arguments,
        ];

        $mock
            ->expects($this->exactly(count($callChain)))
            ->withConsecutive(...$callChain);
    }

    public function mockShellExec(): void
    {
        $mock = $this->getFunctionMock('App\Console\Commands', 'shell_exec');

        $mock
            ->expects($this->once())
            ->with('git ls-remote --get-url origin')
            ->willReturn('https://github.com/ronasit/laravel-helpers.git');
    }
}

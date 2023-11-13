<?php

namespace App\Tests\Support;

use phpmock\phpunit\PHPMock;

trait InitCommandMockTrait
{
    use PHPMock;

    public function mockFilePutContent(array $fileContents = []): void
    {
        $defaultFileContents = [
            '.env.testing' => $this->getFixture('env.testing.yml'),
            '.env.example' => $this->getFixture('env.example.yml'),
            '.env.development' => $this->getFixture('env.development.yml'),
            '.env.ci-testing' => $this->getFixture('env.ci-testing.yml'),
        ];

        $fileContents = $defaultFileContents + $fileContents;

        $mock = $this->getFunctionMock('App\Console\Commands', 'file_put_contents');

        $mock
            ->expects($this->exactly(count($fileContents)))
            ->willReturnCallback(fn ($file) => $fileContents[$file]);
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
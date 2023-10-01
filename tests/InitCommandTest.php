<?php

namespace App\Tests;

use phpmock\phpunit\PHPMock;

class InitCommandTest extends TestCase
{
    use PHPMock;

    public function setUp(): void
    {
        TestCase::setUp();
    }

    public function testRunWithoutAdminCreation()
    {
        $filePutContentsMock = $this->getFunctionMock('App\Console\Commands', 'file_put_contents');

        $filePutContentsMock
            ->expects($this->exactly(4))
            ->withConsecutive(
                ['.env.testing', $this->getFixture('env.testing.yml')],
                ['.env', $this->getFixture('env.yml')],
                ['.env.development', $this->getFixture('env.development.yml')],
                ['.env.ci-testing', $this->getFixture('env.ci-testing.yml')]
            );

        $this->artisan('init my-app')
            ->expectsOutput('Project initialized successfully')
            ->expectsConfirmation('Do you want generate admin user?', 'no')
            ->assertExitCode(0);
    }

    public function testRunWithAdminCreation()
    {
        $filePutContentsMock = $this->getFunctionMock('App\Console\Commands', 'file_put_contents');

        $filePutContentsMock
            ->expects($this->exactly(5))
            ->withConsecutive(
                ['.env.testing', $this->getFixture('env.testing.yml')],
                ['.env', $this->getFixture('env.yml')],
                ['.env.development', $this->getFixture('env.development.yml')],
                ['.env.ci-testing', $this->getFixture('env.ci-testing.yml')],
                ['database/migrations/2018_11_11_111111_add_default_user.php', $this->getFixture('migration.php')]
            );

        $this->artisan('init my-app')
            ->expectsOutput('Project initialized successfully')
            ->expectsConfirmation('Do you want generate admin user?', 'yes')
            ->expectsQuestion('Please enter admin name', 'TestAdmin')
            ->expectsQuestion('Please enter admin email', 'mail@mail.com')
            ->expectsQuestion('Please enter admin password', '123456')
            ->assertExitCode(0);
    }
}

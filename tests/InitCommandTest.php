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

    public function testRunWithoutAdminAndReadmeCreation()
    {
        $filePutContentsMock = $this->getFunctionMock('App\Console\Commands', 'file_put_contents');

        $filePutContentsMock
            ->expects($this->exactly(4))
            ->withConsecutive(
                ['.env.testing', $this->getFixture('env.testing.yml')],
                ['.env', $this->getFixture('env.yml')],
                ['.env.development', $this->getFixture('env.development.yml')],
                ['.env.ci-testing', $this->getFixture('env.ci-testing.yml')],
            );

        $this->artisan('init "My App"')
            ->expectsOutput('Project initialized successfully!')
            ->expectsQuestion('Please enter an application URL', 'https://mysite.com')
            ->expectsConfirmation('Do you want to generate an admin user?')
            ->expectsConfirmation('Do you want to generate a README file?')
            ->assertExitCode(0);
    }

    public function testRunWithAdminAndWithoutReadmeCreation()
    {
        $filePutContentsMock = $this->getFunctionMock('App\Console\Commands', 'file_put_contents');

        $filePutContentsMock
            ->expects($this->exactly(5))
            ->withConsecutive(
                ['.env.testing', $this->getFixture('env.testing.yml')],
                ['.env', $this->getFixture('env.yml')],
                ['.env.development', $this->getFixture('env.development.yml')],
                ['.env.ci-testing', $this->getFixture('env.ci-testing.yml')],
                ['database/migrations/2018_11_11_111111_add_default_user.php', $this->getFixture('migration.php')],
            );

        $this->artisan('init "My App"')
            ->expectsOutput('Project initialized successfully!')
            ->expectsQuestion('Please enter an application URL', 'https://mysite.com')
            ->expectsConfirmation('Do you want to generate an admin user?', 'yes')
            ->expectsQuestion('Please enter an admin name', 'TestAdmin')
            ->expectsQuestion('Please enter an admin email', 'mail@mail.com')
            ->expectsQuestion('Please enter an admin password', '123456')
            ->expectsConfirmation('Do you want to generate a README file?')
            ->assertExitCode(0);
    }

    public function testRunWithAdminAndDefaultReadmeCreation()
    {
        $filePutContentsMock = $this->getFunctionMock('App\Console\Commands', 'file_put_contents');

        $filePutContentsMock
            ->expects($this->exactly(6))
            ->withConsecutive(
                ['.env.testing', $this->getFixture('env.testing.yml')],
                ['.env', $this->getFixture('env.yml')],
                ['.env.development', $this->getFixture('env.development.yml')],
                ['.env.ci-testing', $this->getFixture('env.ci-testing.yml')],
                ['database/migrations/2018_11_11_111111_add_default_user.php', $this->getFixture('migration.php')],
                ['README.md', $this->getFixture('default_readme.md')],
            );

        $this->artisan('init "My App"')
            ->expectsOutput('Project initialized successfully!')
            ->expectsQuestion('Please enter an application URL', 'https://mysite.com')
            ->expectsConfirmation('Do you want to generate an admin user?', 'yes')
            ->expectsQuestion('Please enter an admin name', 'TestAdmin')
            ->expectsQuestion('Please enter an admin email', 'mail@mail.com')
            ->expectsQuestion('Please enter an admin password', '123456')
            ->expectsConfirmation('Do you want to generate a README file?', 'yes')
            ->expectsConfirmation('Do you need a `Resources & Contacts` part?', 'yes')
            ->expectsConfirmation('Are you going to use Issue Tracker?', 'yes')
            ->expectsQuestion('Please enter a Issue Tracker link', '')
            ->expectsConfirmation('Are you going to use Figma?', 'yes')
            ->expectsQuestion('Please enter a Figma link', '')
            ->expectsConfirmation('Are you going to use Sentry?', 'yes')
            ->expectsQuestion('Please enter a Sentry link', '')
            ->expectsConfirmation('Are you going to use DataDog?', 'yes')
            ->expectsQuestion('Please enter a DataDog link', '')
            ->expectsConfirmation('Are you going to use ArgoCD?', 'yes')
            ->expectsQuestion('Please enter a ArgoCD link', '')
            ->expectsConfirmation('Are you going to use Laravel Telescope?', 'yes')
            ->expectsQuestion('Please enter a Laravel Telescope link', '')
            ->expectsQuestion('Please enter a Manager contact', '')
            ->expectsQuestion('Please enter a Code Owner/Team Lead contact', '')
            ->expectsConfirmation('Do you need a `Prerequisites` part?', 'yes')
            ->expectsConfirmation('Do you need a `Getting Started` part?', 'yes')
            ->expectsConfirmation('Do you need an `Environments` part?', 'yes')
            ->expectsConfirmation('Do you need a `Credentials and Access` part?', 'yes')
            ->expectsOutput('README generated successfully!')
            ->expectsOutput('Don`t forget to fill the following empty values:')
            ->expectsOutput('- Issue Tracker link')
            ->expectsOutput('- Figma link')
            ->expectsOutput('- Sentry link')
            ->expectsOutput('- DataDog link')
            ->expectsOutput('- ArgoCD link')
            ->expectsOutput('- Manager contact')
            ->expectsOutput('- Code Owner/Team Lead contact')
            ->assertExitCode(0);
    }

    public function testRunWithAdminAndFullReadmeCreation()
    {
        $filePutContentsMock = $this->getFunctionMock('App\Console\Commands', 'file_put_contents');

        $filePutContentsMock
            ->expects($this->exactly(6))
            ->withConsecutive(
                ['.env.testing', $this->getFixture('env.testing.yml')],
                ['.env', $this->getFixture('env.yml')],
                ['.env.development', $this->getFixture('env.development.yml')],
                ['.env.ci-testing', $this->getFixture('env.ci-testing.yml')],
                ['database/migrations/2018_11_11_111111_add_default_user.php', $this->getFixture('migration.php')],
                ['README.md', $this->getFixture('full_readme.md')],
            );

        $this->artisan('init "My App"')
            ->expectsOutput('Project initialized successfully!')
            ->expectsQuestion('Please enter an application URL', 'https://mysite.com')
            ->expectsConfirmation('Do you want to generate an admin user?', 'yes')
            ->expectsQuestion('Please enter an admin name', 'TestAdmin')
            ->expectsQuestion('Please enter an admin email', 'mail@mail.com')
            ->expectsQuestion('Please enter an admin password', '123456')
            ->expectsConfirmation('Do you want to generate a README file?', 'yes')
            ->expectsConfirmation('Do you need a `Resources & Contacts` part?', 'yes')
            ->expectsConfirmation('Are you going to use Issue Tracker?', 'yes')
            ->expectsQuestion('Please enter a Issue Tracker link', 'https://gitlab.com/my-project')
            ->expectsConfirmation('Are you going to use Figma?', 'yes')
            ->expectsQuestion('Please enter a Figma link', 'https://figma.com/my-project')
            ->expectsConfirmation('Are you going to use Sentry?', 'yes')
            ->expectsQuestion('Please enter a Sentry link', 'https://sentry.com/my-project')
            ->expectsConfirmation('Are you going to use DataDog?', 'yes')
            ->expectsQuestion('Please enter a DataDog link', 'https://datadoghq.com/my-project')
            ->expectsConfirmation('Are you going to use ArgoCD?', 'yes')
            ->expectsQuestion('Please enter a ArgoCD link', 'https://argocd.com/my-project')
            ->expectsConfirmation('Are you going to use Laravel Telescope?', 'yes')
            ->expectsQuestion('Please enter a Laravel Telescope link', 'https://mypsite.com/telescope-link')
            ->expectsQuestion('Please enter a Manager contact', 'manager@mail.com')
            ->expectsQuestion('Please enter a Code Owner/Team Lead contact', 'lead@mail.com')
            ->expectsConfirmation('Do you need a `Prerequisites` part?', 'yes')
            ->expectsConfirmation('Do you need a `Getting Started` part?', 'yes')
            ->expectsConfirmation('Do you need an `Environments` part?', 'yes')
            ->expectsConfirmation('Do you need a `Credentials and Access` part?', 'yes')
            ->expectsOutput('README generated successfully!')
            ->assertExitCode(0);
    }
}
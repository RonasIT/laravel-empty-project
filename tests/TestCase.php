<?php

namespace App\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;
use RonasIT\Support\Tests\TestCase as BaseTestCase;
use RonasIT\Support\AutoDoc\Tests\AutoDocTestCaseTrait;

abstract class TestCase extends BaseTestCase
{
    use AutoDocTestCaseTrait;

    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication(): Application
    {
        /** @var Application $app */
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->loadEnvironmentFrom('.env.testing');
        $app->make(Kernel::class)->bootstrap();

        $this->truncateExceptTables[] = 'roles';
        $this->prepareSequencesExceptTables[] = 'roles';

        return $app;
    }

    public function tearDown(): void
    {
        $this->saveDocumentation();

        parent::tearDown();
    }
}

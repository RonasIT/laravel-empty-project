<?php

namespace App\Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use RonasIT\Support\AutoDoc\Tests\AutoDocTestCaseTrait;
use RonasIT\Support\Tests\TestCase as BaseTestCase;

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
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->loadEnvironmentFrom('.env.testing');
        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}

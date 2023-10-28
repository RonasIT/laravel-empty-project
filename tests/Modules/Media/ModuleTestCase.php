<?php

namespace App\Tests\Modules\Media;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Support\Arr;
use RonasIT\Support\AutoDoc\Tests\AutoDocTestCaseTrait;
use RonasIT\Support\Tests\TestCase;

abstract class ModuleTestCase extends TestCase
{
    use AutoDocTestCaseTrait;

    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication(): Application
    {
        $app = require __DIR__ . '/../../../bootstrap/app.php';

        $app->loadEnvironmentFrom('.env.testing');
        $app->make(Kernel::class)->bootstrap();

        $this->truncateExceptTables = ['migrations', 'password_resets', 'roles'];
        $this->prepareSequencesExceptTables = ['migrations', 'password_resets', 'settings', 'roles'];

        return $app;
    }

    public function getFixturePath($fixtureName): string
    {
        $class = get_class($this);
        $explodedClass = explode('\\', $class);
        $className = Arr::last($explodedClass);

        return base_path("tests/Modules/Media/fixtures/{$className}/{$fixtureName}");
    }
}

<?php

namespace App\Tests\Modules\Media;

use App\Tests\TestCase;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Support\Arr;
use RonasIT\Support\AutoDoc\Tests\AutoDocTestCaseTrait;

abstract class ModuleTestCase extends TestCase
{
    use AutoDocTestCaseTrait;

    public function tearDown(): void
    {
        $this->saveDocumentation();

        parent::tearDown();
    }

    public function getFixturePath($fixtureName): string
    {
        $class = get_class($this);
        $explodedClass = explode('\\', $class);
        $className = Arr::last($explodedClass);

        return base_path("tests/Modules/Media/fixtures/{$className}/{$fixtureName}");
    }
}

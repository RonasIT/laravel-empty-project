<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

/**
 * @codeCoverageIgnore
 */
class DevDependenciesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (config('app.env') === 'local') {
            // To use "require-dev" dependencies, their providers should be registered via full namespace
            App::register(\RonasIT\Support\EntityGeneratorServiceProvider::class);
            App::register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    public function register(): void
    {
    }
}

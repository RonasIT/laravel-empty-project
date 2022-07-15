<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use RonasIT\Support\EntityGeneratorServiceProvider;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;

class DevDependenciesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (config('app.env') === 'local') {
            App::register(EntityGeneratorServiceProvider::class);
            App::register(IdeHelperServiceProvider::class);
        }
    }

    public function register(): void
    {
    }
}

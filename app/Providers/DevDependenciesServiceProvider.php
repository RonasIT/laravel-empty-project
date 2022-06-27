<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class DevDependenciesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (config('app.env') === 'local') {
            App::register(\RonasIT\Support\EntityGeneratorServiceProvider::class);
            App::register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    public function register()
    {
    }
}

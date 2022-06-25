<?php

namespace App\Modules\Media\Providers;

use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'media');
        $this->mergeConfigFrom(__DIR__ . '/../config/media.php', 'media');
    }

    public function register()
    {

    }
}
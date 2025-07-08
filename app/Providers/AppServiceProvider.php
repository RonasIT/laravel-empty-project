<?php

namespace App\Providers;

use App\Enums\VersionEnum;
use Illuminate\Support\ServiceProvider;
use RonasIT\Support\Contracts\VersionEnumContract;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
    }

    public function register()
    {
        $this->app->bind(VersionEnumContract::class, fn () => VersionEnum::class);
    }
}

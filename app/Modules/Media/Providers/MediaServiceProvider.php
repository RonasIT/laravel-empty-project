<?php

namespace App\Modules\Media\Providers;

use App\Modules\Media\Contracts\Controllers\MediaControllerContract;
use App\Modules\Media\Contracts\Requests\BulkCreateMediaRequestContract;
use App\Modules\Media\Contracts\Requests\CreateMediaRequestContract;
use App\Modules\Media\Contracts\Requests\DeleteMediaRequestContract;
use App\Modules\Media\Contracts\Requests\SearchMediaRequestContract;
use App\Modules\Media\Contracts\Services\MediaServiceContract;
use App\Modules\Media\Http\Controllers\MediaController;
use App\Modules\Media\Http\Requests\BulkCreateMediaRequest;
use App\Modules\Media\Http\Requests\CreateMediaRequest;
use App\Modules\Media\Http\Requests\DeleteMediaRequest;
use App\Modules\Media\Http\Requests\SearchMediaRequest;
use App\Modules\Media\Services\MediaService;
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

    public function register(): void
    {
        $this->app->bind(CreateMediaRequestContract::class, CreateMediaRequest::class);
        $this->app->bind(BulkCreateMediaRequestContract::class, BulkCreateMediaRequest::class);
        $this->app->bind(SearchMediaRequestContract::class, SearchMediaRequest::class);
        $this->app->bind(DeleteMediaRequestContract::class, DeleteMediaRequest::class);

        $this->app->bind(MediaServiceContract::class, MediaService::class);
        $this->app->bind(MediaControllerContract::class, MediaController::class);
    }
}
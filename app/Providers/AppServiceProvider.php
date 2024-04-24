<?php

namespace App\Providers;

use App\Enums\VersionEnum;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        /**
         * Specify that the route version must be in the range of given values inclusive.
         *
         * @param VersionEnum|null $start
         * @param VersionEnum|null $end
         * @param string $param (default is 'version')
         * @return Route
         */
        Route::macro('versionRange', function (?VersionEnum $start, ?VersionEnum $end, string $param = 'version') {
            $versions = array_diff(VersionEnum::values(), config('app.disabled_api_versions'));

            $versions = array_filter($versions, function ($version) use ($start, $end) {
                $result = true;

                if (!empty($start)) {
                    $result &= version_compare($version, $start->value, '>=');
                }

                if (!empty($end)) {
                    $result &= version_compare($version, $end->value, '<=');
                }

                return $result;
            });

            return $this->whereIn($param, $versions);
        });

        Route::macro('versionFrom', fn (VersionEnum $from) => $this::versionRange($from, null));

        Route::macro('versionTo', fn (VersionEnum $to) => $this::versionRange(null, $to));

        RouteFacade::macro('version', fn (VersionEnum $version) => RouteFacade::prefix('v' . $version->value));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

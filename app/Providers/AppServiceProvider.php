<?php

namespace App\Providers;

use App\Enums\VersionEnum;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\RouteRegistrar;

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
         * @param string|null $param (default is 'version')
         * @param Route|null $instance
         * @return Route|RouteRegistrar
         */
        $versionRange = function (?VersionEnum $start, ?VersionEnum $end, ?string $param, Route $instance = null) {
            if (!$param) {
                $param = 'version';
            }

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

            return (!empty($instance))
                ? $instance->whereIn($param, $versions)
                : RouteFacade::whereIn($param, $versions);
        };

        Route::macro('versionRange', fn (VersionEnum $from, VersionEnum $to, $param = null) => $versionRange($from, $to, $param, $this));

        Route::macro('versionFrom', function (VersionEnum $from, $param = null) use ($versionRange) {
            return $versionRange($from, null, $param, $this);
        });

        Route::macro('versionTo', function (VersionEnum $to, $param = null) use ($versionRange) {
            return $versionRange(null, $to, $param, $this);
        });

        RouteFacade::macro('versionRange', function (VersionEnum $from, VersionEnum $to, $param = null) use ($versionRange) {
            return $versionRange($from, $to, $param);
        });
        RouteFacade::macro('versionFrom', function (VersionEnum $from, $param = null) use ($versionRange) {
            return $versionRange($from, null, $param);
        });
        RouteFacade::macro('versionTo', function (VersionEnum $to, $param = null) use ($versionRange) {
            return $versionRange(null, $to, $param);
        });
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

<?php

namespace App\Support;

use App\Enums\VersionEnum;
use Illuminate\Support\Facades\Route;

class Version
{
    public static function current(): string
    {
        $route = Route::getRoutes()->match(request());

        return $route->parameters()['version'] ?? str_replace('/v', '', $route->getPrefix());
    }

    public static function is(VersionEnum $checkedVersion): bool
    {
        return version_compare($checkedVersion->value, self::current(), '==');
    }

    public static function between(VersionEnum $from, VersionEnum $to): bool
    {
        $version = self::current();

        return version_compare($version, $from->value, '>=') && version_compare($version, $to->value, '<=');
    }

    public static function gte(VersionEnum $from): bool
    {
        return version_compare(self::current(), $from->value, '>=');
    }

    public static function lte(VersionEnum $to): bool
    {
        return version_compare(self::current(), $to->value, '<=');
    }
}

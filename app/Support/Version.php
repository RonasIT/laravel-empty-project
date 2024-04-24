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
        return $checkedVersion->value === self::current();
    }

    public static function between(VersionEnum $from, VersionEnum $to): bool
    {
        $version = self::current();

        return $version >= $from->value && $version <= $to->value;
    }

    public static function from(VersionEnum $from): bool
    {
        return self::current() >= $from->value;
    }

    public static function to(VersionEnum $to): bool
    {
        return self::current() <= $to->value;
    }
}

<?php

namespace App\Enums;

use RonasIT\Support\Contracts\VersionEnumContract;
use RonasIT\Support\Traits\EnumTrait;

enum VersionEnum: string implements VersionEnumContract
{
    use EnumTrait;

    case v0_1 = '0.1';

    public static function last(): VersionEnum
    {
        return self::v0_1;
    }
}

<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum VersionEnum: string
{
    use EnumTrait;

    case v0_1 = '0.1';
}

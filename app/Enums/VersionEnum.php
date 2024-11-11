<?php

namespace App\Enums;

use RonasIT\Support\Traits\EnumTrait;

enum VersionEnum: string
{
    use EnumTrait;

    case v0_1 = '0.1';
}

<?php

namespace App\Enums;

use RonasIT\Support\Traits\EnumTrait;

enum QueueEnum: string
{
    use EnumTrait;

    case Mails = 'mails';
}

<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum QueueEnum: string
{
    use EnumTrait;

    case Mails = 'mails';
}

<?php

namespace App\Modules\Media\Contracts\Resources;

interface MediaResourceContract
{
    public function toArray($request): array;
}

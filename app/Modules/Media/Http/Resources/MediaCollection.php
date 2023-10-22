<?php

namespace App\Modules\Media\Http\Resources;

use App\Modules\Media\Contracts\Resources\MediaCollectionResourceContract;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MediaCollection extends ResourceCollection implements MediaCollectionResourceContract
{
    public $collects = MediaResource::class;
}

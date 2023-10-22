<?php

namespace App\Modules\Media\Http\Resources;

use App\Modules\Media\Contracts\Resources\MediaCollectionContract;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MediaCollection extends ResourceCollection implements MediaCollectionContract
{
    public $collects = MediaResource::class;
}

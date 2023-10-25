<?php

namespace App\Modules\Media\Http\Resources;

use App\Modules\Media\Contracts\Resources\MediaCollectionContract;
use App\Modules\Media\Contracts\Resources\MediaListResourceContract;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MediaCollection extends ResourceCollection implements MediaCollectionContract, MediaListResourceContract
{
    public $collects = MediaResource::class;
}

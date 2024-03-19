<?php

namespace App\Http\Resources\Media;

use App\Modules\Media\Http\Resources\MediaResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MediaCollectionResource extends ResourceCollection
{
    public $collects = MediaResource::class;
}

<?php

namespace App\Modules\Media\Http\Resources;

use App\Modules\Media\Contracts\Resources\MediaListResourceContract;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MediaListResource extends ResourceCollection implements MediaListResourceContract
{
    public $collects = MediaResource::class;

    public function toArray($request): array
    {
        return $this->collection->toArray();
    }
}

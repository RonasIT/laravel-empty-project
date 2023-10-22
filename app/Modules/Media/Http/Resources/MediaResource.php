<?php

namespace App\Modules\Media\Http\Resources;

use App\Modules\Media\Contracts\Resources\MediaResourceContract;

class MediaResource extends BaseResource implements MediaResourceContract
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'link' => $this->resource->link,
            'name' => $this->resource->name,
            'is_public' => $this->when($request->user() && $request->user()->isAdmin(), $this->resource->is_public),
            'owner_id' => $this->when($request->user() && $request->user()->isAdmin(), $this->resource->owner_id),
            'meta' => $this->resource->meta,
        ];
    }
}

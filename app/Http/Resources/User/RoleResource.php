<?php

namespace App\Http\Resources\User;

use App\Http\Resources\BaseJsonResource;

class RoleResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
        ];
    }
}

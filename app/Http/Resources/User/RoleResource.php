<?php

namespace App\Http\Resources\User;

use RonasIT\Support\Http\BaseResource;

class RoleResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
        ];
    }
}

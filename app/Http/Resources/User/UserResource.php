<?php

namespace App\Http\Resources\User;

use App\Http\Resources\BaseJsonResource;

class UserResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'role_id' => $this->resource->role_id,
            'role' => RoleResource::make($this->whenLoaded('role')),
        ];
    }
}

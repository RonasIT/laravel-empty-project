<?php

namespace App\Http\Resources\User;

use RonasIT\Support\Http\BaseResource;

class UserResource extends BaseResource
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

<?php

namespace App\Http\Resources\User;

use App\Http\Resources\BaseJsonResource;
use Illuminate\Http\Response;

class UserResource extends BaseJsonResource
{
    public function withResponse($request, $response): void
    {
        $response->setStatusCode(Response::HTTP_OK);
    }

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

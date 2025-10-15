<?php

namespace App\Http\Resources\Setting;

use RonasIT\Support\Http\BaseResource;

class SettingResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'name' => $this->resource->name,
            'value' => $this->resource->value,
        ];
    }
}

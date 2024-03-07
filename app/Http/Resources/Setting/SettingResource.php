<?php

namespace App\Http\Resources\Setting;

use App\Http\Resources\BaseJsonResource;

class SettingResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'name' => $this->resource->name,
            'value' => $this->resource->value,
        ];
    }
}

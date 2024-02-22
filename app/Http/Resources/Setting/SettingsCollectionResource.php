<?php

namespace App\Http\Resources\Setting;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SettingsCollectionResource extends ResourceCollection
{
    public $collects = SettingResource::class;
}

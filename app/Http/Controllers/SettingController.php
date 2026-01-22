<?php

namespace App\Http\Controllers;

use App\Http\Requests\Setting\GetSettingRequest;
use App\Http\Requests\Setting\SearchSettingRequest;
use App\Http\Resources\Setting\SettingResource;
use App\Http\Resources\Setting\SettingsCollectionResource;
use App\Services\SettingService;

class SettingController extends Controller
{
    public function get(GetSettingRequest $request, SettingService $service, string $key): SettingResource
    {
        $result = $service->findBy('name', $key);

        return SettingResource::make($result);
    }

    public function search(SearchSettingRequest $request, SettingService $service): SettingsCollectionResource
    {
        $result = $service->search($request->onlyValidated());

        return SettingsCollectionResource::make($result);
    }
}

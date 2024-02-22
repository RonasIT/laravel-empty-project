<?php

namespace App\Http\Controllers;

use App\Http\Requests\Setting\GetSettingRequest;
use App\Http\Requests\Setting\SearchSettingRequest;
use App\Http\Requests\Setting\UpdateSettingRequest;
use App\Http\Resources\Setting\SettingsCollectionResource;
use App\Http\Resources\Setting\SettingResource;
use App\Services\SettingService;
use Illuminate\Http\Response;

class SettingController extends Controller
{
    public function get(GetSettingRequest $request, SettingService $service, string $key): SettingResource
    {
        $result = $service->findBy('name', $key);

        return SettingResource::make($result);
    }

    public function update(UpdateSettingRequest $request, SettingService $service, string $key): Response
    {
        $service->update(
            ['name' => $key],
            ['value' => $request->all()]
        );

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function search(SearchSettingRequest $request, SettingService $service): SettingsCollectionResource
    {
        $result = $service->search($request->onlyValidated());

        return SettingsCollectionResource::make($result);
    }
}

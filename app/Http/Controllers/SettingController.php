<?php

namespace App\Http\Controllers;

use App\Services\SettingService;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Setting\GetSettingRequest;
use App\Http\Requests\Setting\SearchSettingRequest;
use App\Http\Requests\Setting\UpdateSettingRequest;

class SettingController extends Controller
{
    public function get(GetSettingRequest $request, SettingService $service, $key)
    {
        $result = $service->findBy('name', $key);

        return response()->json($result);
    }

    public function update(UpdateSettingRequest $request, SettingService $service, $key)
    {
        $service->update(
            ['name' => $key],
            ['value' => $request->all()]
        );

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function search(SearchSettingRequest $request, SettingService $service)
    {
        $result = $service->search($request->onlyValidated());

        return response()->json($result);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Setting\GetSettingRequest;
use App\Http\Requests\Setting\SearchSettingRequest;
use App\Http\Requests\Setting\UpdateSettingRequest;
use App\Services\SettingService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SettingController extends Controller
{
    public function get(GetSettingRequest $request, SettingService $service, string $key): JsonResponse
    {
        $result = $service->findBy('name', $key);

        return response()->json($result);
    }

    public function update(UpdateSettingRequest $request, SettingService $service, string $key): Response
    {
        $service->update(
            ['name' => $key],
            ['value' => $request->all()]
        );

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function search(SearchSettingRequest $request, SettingService $service): JsonResponse
    {
        $result = $service->search($request->onlyValidated());

        return response()->json($result);
    }
}

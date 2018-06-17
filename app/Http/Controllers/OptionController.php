<?php

namespace App\Http\Controllers;

use App\Http\Requests\Option\CreateOptionRequest;
use App\Http\Requests\Option\DeleteOptionRequest;
use App\Http\Requests\Option\GetOptionRequest;
use App\Http\Requests\Option\SearchOptionRequest;
use App\Http\Requests\Option\UpdateOptionRequest;
use App\Services\OptionService;
use Symfony\Component\HttpFoundation\Response;

class OptionController extends Controller
{
    public function create(CreateOptionRequest $request, OptionService $service)
    {
        $data = $request->all();

        $result = $service->create($data);

        return response()->json($result);
    }

    public function get(GetOptionRequest $request, OptionService $service, $key)
    {
        $result = $service->first(['key' => $key]);

        return response()->json($result);
    }

    public function update(UpdateOptionRequest $request, OptionService $service, $key)
    {
        $service->update(
            ['key' => $key],
            ['value' => $request->all()]
        );

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function delete(DeleteOptionRequest $request, OptionService $service, $key)
    {
        $service->delete(['key' => $key]);

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function search(SearchOptionRequest $request, OptionService $service)
    {
        $result = $service->search($request->all());

        return response($result);
    }
}
<?php

namespace App\Http\Controllers;

use App\Http\Requests\tests\CreatetestRequest;
use App\Http\Requests\tests\GettestRequest;
use App\Http\Requests\tests\UpdatetestRequest;
use App\Http\Requests\tests\DeletetestRequest;
use App\Http\Requests\tests\SearchtestRequest;
use App\Services\testService;
use Symfony\Component\HttpFoundation\Response;

class testController extends Controller
{
    public function create(CreatetestRequest $request, testService $service)
    {
        $data = $request->all();

        $result = $service->create($data);

        return response()->json($result);
    }

    public function get(GettestRequest $request, testService $service, $id)
    {
        $result = $service->first(['id' => $id]);

        return response()->json($result);
    }

    public function update(UpdatetestRequest $request, testService $service, $id)
    {
        $service->update(
            ['id' => $id],
            $request->all()
        );

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function delete(DeletetestRequest $request, testService $service, $id)
    {
        $service->delete(['id' => $id]);

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function search(SearchtestRequest $request, testService $service)
    {
        $result = $service->search($request->all());

        return response($result);
    }
}
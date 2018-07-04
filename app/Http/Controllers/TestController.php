<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tests\CreateTestRequest;
use App\Http\Requests\Tests\GetTestRequest;
use App\Http\Requests\Tests\UpdateTestRequest;
use App\Http\Requests\Tests\DeleteTestRequest;
use App\Http\Requests\Tests\SearchTestRequest;
use App\Services\TestService;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{
    public function create(CreateTestRequest $request, TestService $service)
    {
        $data = $request->all();

        $result = $service->create($data);

        return response()->json($result);
    }

    public function get(GetTestRequest $request, TestService $service, $id)
    {
        $result = $service->first(['id' => $id]);

        return response()->json($result);
    }

    public function update(UpdateTestRequest $request, TestService $service, $id)
    {
        $service->update(
            ['id' => $id],
            $request->all()
        );

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function delete(DeleteTestRequest $request, TestService $service, $id)
    {
        $service->delete(['id' => $id]);

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function search(SearchTestRequest $request, TestService $service)
    {
        $result = $service->search($request->all());

        return response($result);
    }
}
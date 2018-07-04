<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\GetUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Requests\Users\GetUserProfileRequest;
use App\Http\Requests\Users\UpdateProfileRequest;
use App\Http\Requests\Users\DeleteUserRequest;
use App\Http\Requests\Users\SearchUserRequest;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function create(CreateUserRequest $request, UserService $service)
    {
        $data = $request->all();

        $result = $service->create($data);

        return response()->json($result);
    }

    public function get(GetUserRequest $request, UserService $service, $id)
    {
        $result = $service->first(['id' => $id]);

        return response()->json($result);
    }

    public function update(UpdateUserRequest $request, UserService $service, $id)
    {
        $service->update(
            ['id' => $id],
            $request->except('password')
        );

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function profile(GetUserProfileRequest $request, UserService $service)
    {
        $result = $service->first(['id' => $request->user()->id]);

        return response()->json($result);
    }

    public function updateProfile(UpdateProfileRequest $request, UserService $service)
    {
        $service->update(
            ['id' => $request->user()->id],
            $request->all()
        );

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function delete(DeleteUserRequest $request, UserService $service, $id)
    {
        $service->delete(['id' => $id]);

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function search(SearchUserRequest $request, UserService $service)
    {
        $result = $service->search($request->all());

        return response($result);
    }
}
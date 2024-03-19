<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\DeleteProfileRequest;
use App\Http\Requests\Users\DeleteUserRequest;
use App\Http\Requests\Users\GetUserProfileRequest;
use App\Http\Requests\Users\GetUserRequest;
use App\Http\Requests\Users\SearchUserRequest;
use App\Http\Requests\Users\UpdateProfileRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UsersCollectionResource;
use App\Services\UserService;
use App\Traits\TokenTrait;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    use TokenTrait;

    public function create(CreateUserRequest $request, UserService $service): UserResource
    {
        $data = $request->onlyValidated();

        $result = $service->create($data);

        return UserResource::make($result);
    }

    public function get(GetUserRequest $request, UserService $service, int $id): UserResource
    {
        $result = $service
            ->with($request->input('with', []))
            ->find($id);

        return UserResource::make($result);
    }

    public function update(UpdateUserRequest $request, UserService $service, int $id): Response
    {
        $service->update($id, $request->onlyValidated());

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function profile(GetUserProfileRequest $request, UserService $service): UserResource
    {
        $result = $service
            ->with($request->input('with', []))
            ->find($request->user()->id);

        return UserResource::make($result);
    }

    public function updateProfile(UpdateProfileRequest $request, UserService $service): Response
    {
        $service->update($request->user()->id, $request->onlyValidated());

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function deleteProfile(DeleteProfileRequest $request, UserService $service): Response
    {
        $service->delete($request->user()->id);

        $tokenCookie = $this->makeAuthorizationTokenExpiredCookie();

        return response('', Response::HTTP_NO_CONTENT)->withCookie($tokenCookie);
    }

    public function delete(DeleteUserRequest $request, UserService $service, int $id): Response
    {
        $service->delete($id);

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function search(SearchUserRequest $request, UserService $service): UsersCollectionResource
    {
        $result = $service->search($request->onlyValidated());

        return new UsersCollectionResource($result);
    }
}

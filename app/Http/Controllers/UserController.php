<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\DeleteProfileRequest;
use App\Http\Requests\Users\GetUserProfileRequest;
use App\Http\Requests\Users\GetUserRequest;
use App\Http\Requests\Users\SearchUserRequest;
use App\Http\Requests\Users\UpdateProfileRequest;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UsersCollectionResource;
use App\Services\UserService;
use App\Traits\TokenTrait;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    use TokenTrait;

    public function get(GetUserRequest $request, UserService $service, int $id): UserResource
    {
        $result = $service->find($id);

        return UserResource::make($result);
    }

    public function profile(GetUserProfileRequest $request, UserService $service): UserResource
    {
        $result = $service->find($request->user()->id);

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

    public function search(SearchUserRequest $request, UserService $service): UsersCollectionResource
    {
        $result = $service->search($request->onlyValidated());

        return new UsersCollectionResource($result);
    }
}

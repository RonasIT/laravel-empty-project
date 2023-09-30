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
use App\Services\UserService;
use App\Traits\TokenTrait;
use Illuminate\Http\JsonResponse;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class UserController extends Controller
{
    use TokenTrait;

    public function create(CreateUserRequest $request, UserService $service): JsonResponse
    {
        $data = $request->onlyValidated();

        $result = $service->create($data);

        return response()->json($result);
    }

    public function get(GetUserRequest $request, UserService $service, int $id): JsonResponse
    {
        $result = $service
            ->with($request->input('with', []))
            ->find($id);

        return response()->json($result);
    }

    public function update(UpdateUserRequest $request, UserService $service, int $id): Response
    {
        $service->update($id, $request->onlyValidated());

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function profile(GetUserProfileRequest $request, UserService $service): JsonResponse
    {
        $result = $service
            ->with($request->input('with', []))
            ->find($request->user()->id);

        return response()->json($result);
    }

    public function updateProfile(UpdateProfileRequest $request, UserService $service): Response
    {
        $service->update($request->user()->id, $request->onlyValidated());

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function deleteProfile(DeleteProfileRequest $request, UserService $service, JWTAuth $auth): Response
    {
        try {
            $service->delete($request->user()->id);

            $auth->parseToken();
            $auth->invalidate(true);
            $auth->unsetToken();

            $tokenCookie = $this->makeAuthorizationTokenExpiredCookie();

            return response('', Response::HTTP_NO_CONTENT)->withCookie($tokenCookie);
        } catch (JWTException $e) {
            throw new UnauthorizedHttpException('jwt-auth', $e->getMessage(), $e, $e->getCode());
        }
    }

    public function delete(DeleteUserRequest $request, UserService $service, int $id): Response
    {
        $service->delete($id);

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function search(SearchUserRequest $request, UserService $service): JsonResponse
    {
        $result = $service->search($request->onlyValidated());

        return response()->json($result);
    }
}

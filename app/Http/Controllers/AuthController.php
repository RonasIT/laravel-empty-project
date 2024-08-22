<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LogoutRequest;
use App\Http\Requests\Auth\RefreshTokenRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Requests\Auth\RestorePasswordRequest;
use App\Http\Resources\Auth\SuccessLoginResource;
use App\Http\Resources\Auth\RefreshTokenResource;
use Illuminate\Http\Response;
use App\Services\UserService;
use App\Traits\TokenTrait;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class AuthController extends Controller
{
    use TokenTrait;

    public function login(LoginRequest $request, UserService $service, JWTAuth $auth): SuccessLoginResource
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->input('remember', false);

        $token = $auth->attempt($credentials);

        if ($token === false) {
            throw new UnauthorizedHttpException('jwt-auth');
        }

        $user = $service->first(['email' => $request->input('email')]);

        $tokenCookie = $this->makeAuthorizationTokenCookie($token, $remember);

        return SuccessLoginResource::make($token, $user, $tokenCookie);
    }

    public function register(RegisterUserRequest $request, UserService $service, JWTAuth $auth): SuccessLoginResource
    {
        $user = $service->create($request->onlyValidated());

        $credentials = $request->only('email', 'password');
        $remember = $request->input('remember', false);

        $token = $auth->attempt($credentials);
        $tokenCookie = $this->makeAuthorizationTokenCookie($token, $remember);

        return SuccessLoginResource::make($token, $user, $tokenCookie);
    }

    public function refreshToken(RefreshTokenRequest $request, JWTAuth $auth): RefreshTokenResource
    {
        $remember = $request->input('remember', false);

        try {
            $refreshedOldToken = $auth->parseToken()->refresh();
            $auth->setToken($refreshedOldToken);
            $auth->authenticate();

            $user = $auth->user();

            $auth->invalidate(true);
            $auth->unsetToken();

            $newToken = $auth->fromUser($user);
            $tokenCookie = $this->makeAuthorizationTokenCookie($newToken, $remember);

            return RefreshTokenResource::make($newToken, $tokenCookie);
        } catch (JWTException $e) {
            throw new UnauthorizedHttpException('jwt-auth', $e->getMessage(), $e, $e->getCode());
        }
    }

    public function logout(LogoutRequest $request): Response
    {
        $tokenCookie = $this->makeAuthorizationTokenExpiredCookie();

        return response('', Response::HTTP_NO_CONTENT)->withCookie($tokenCookie);
    }

    public function forgotPassword(ForgotPasswordRequest $request, UserService $service): Response
    {
        $status = $service->forgotPassword($request->input('email'));

        if ($status !== Password::RESET_LINK_SENT) {
            throw new UnprocessableEntityHttpException(__($status));
        }

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function restorePassword(RestorePasswordRequest $request, UserService $service): Response
    {
        $status = $service->restorePassword($request->onlyValidated());

        if ($status !== Password::PASSWORD_RESET) {
            throw new UnprocessableEntityHttpException(__($status));
        }

        return response('', Response::HTTP_NO_CONTENT);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\CheckRestoreTokenRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RefreshTokenRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Requests\Auth\RestorePasswordRequest;
use App\Services\UserService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Response;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    public function login(LoginRequest $request, UserService $service, JWTAuth $auth)
    {
        $credentials = $this->credentials($request);
        $token = $auth->attempt($credentials);

        if ($token === false) {
            return response()->json([
                'message' => 'Authorization failed'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = $service->first(['email' => $request->input('email')]);

        return response()->json([
            'token' => $token,
            'ttl' => config('jwt.ttl'),
            'refresh_ttl' => config('jwt.refresh_ttl'),
            'user' => $user
        ]);
    }

    public function register(RegisterUserRequest $request, UserService $service, JWTAuth $auth)
    {
        $user = $service->create($request->all());

        $credentials = $this->credentials($request);
        $token = $auth->attempt($credentials);

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }

    public function refreshToken(RefreshTokenRequest $request)
    {
        return response('', Response::HTTP_NO_CONTENT);
    }

    public function forgotPassword(ForgotPasswordRequest $request, UserService $service)
    {
        $service->forgotPassword($request->input('email'));

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function restorePassword(RestorePasswordRequest $request, UserService $service)
    {
        $service->restorePassword(
            $request->input('token'),
            $request->input('password')
        );

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function checkRestoreToken(CheckRestoreTokenRequest $request)
    {
        return response('', Response::HTTP_NO_CONTENT);
    }

    public function username()
    {
        return 'email';
    }
}
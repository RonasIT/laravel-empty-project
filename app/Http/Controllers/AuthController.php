<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\CheckRestoreTokenRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LogoutRequest;
use App\Http\Requests\Auth\RefreshTokenRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Requests\Auth\RestorePasswordRequest;
use App\Services\UserService;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    public function login(LoginRequest $request, UserService $service, JWTAuth $auth)
    {
        $credentials = $request->only('email', 'password');
        $token = $auth->attempt($credentials);

        if ($token === false) {
            return response()->json([
                'message' => 'Authorization failed'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = $service->first(['email' => $request->input('email')]);

        $tokenCookie = $this->authorizationTokenCookie($token);

        return response()
            ->json([
                'token' => $token,
                'ttl' => config('jwt.ttl'),
                'refresh_ttl' => config('jwt.refresh_ttl'),
                'user' => $user
            ])
            ->withCookie($tokenCookie);
    }

    public function register(RegisterUserRequest $request, UserService $service, JWTAuth $auth)
    {
        $user = $service->create($request->onlyValidated());

        $credentials = $request->only('email', 'password');
        $token = $auth->attempt($credentials);
        $tokenCookie = $this->authorizationTokenCookie($token);

        return response()
            ->json([
                'token' => $token,
                'user' => $user
            ])
            ->withCookie($tokenCookie);
    }

    public function refreshToken(RefreshTokenRequest $request, JWTAuth $auth)
    {
        try {
            $token = $auth->parseToken()->refresh();
            $tokenCookie = $this->authorizationTokenCookie($token);

            return response()
                ->json([
                    'token' => $token
                ])
                ->withHeaders([
                    'Authorization' => "Bearer {$token}"
                ])
                ->withCookie($tokenCookie);
        } catch (JWTException $e) {
            throw new UnauthorizedHttpException('jwt-auth', $e->getMessage(), $e, $e->getCode());
        }
    }

    public function logout(LogoutRequest $request, JWTAuth $auth)
    {
        try {
            $auth->parseToken();
            $auth->invalidate(true);
            $auth->unsetToken();

            $tokenCookie = $this->authorizationForgetTokenCookie();

            return response('', Response::HTTP_NO_CONTENT)->withCookie($tokenCookie);
        } catch (JWTException $e) {
            throw new UnauthorizedHttpException('jwt-auth', $e->getMessage(), $e, $e->getCode());
        }
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

    private function authorizationTokenCookie($token)
    {
        return $this->makeAuthorizationTokenCookie($token);
    }

    private function authorizationForgetTokenCookie()
    {
        return $this->makeAuthorizationTokenCookie(null, true);
    }

    private function makeAuthorizationTokenCookie(string $token, bool $forget = false)
    {
        $minutes = $forget ? -2628000 : 0;

        return cookie('token', $token, $minutes, null, null, true, true, false, 'None');
    }
}

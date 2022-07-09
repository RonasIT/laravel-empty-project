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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    public function login(LoginRequest $request, UserService $service, JWTAuth $auth): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->input('remember', false);

        $token = $auth->attempt($credentials);

        if ($token === false) {
            return response()->json([
                'message' => 'Authorization failed'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = $service->first(['email' => $request->input('email')]);

        $tokenCookie = $this->makeAuthorizationTokenCookie($token, $remember);

        return response()
            ->json([
                'token' => $token,
                'ttl' => config('jwt.ttl'),
                'refresh_ttl' => config('jwt.refresh_ttl'),
                'user' => $user
            ])
            ->withCookie($tokenCookie);
    }

    public function register(RegisterUserRequest $request, UserService $service, JWTAuth $auth): JsonResponse
    {
        $user = $service->create($request->onlyValidated());

        $credentials = $request->only('email', 'password');
        $token = $auth->attempt($credentials);
        $tokenCookie = $this->makeAuthorizationTokenCookie($token);

        return response()
            ->json([
                'token' => $token,
                'ttl' => config('jwt.ttl'),
                'refresh_ttl' => config('jwt.refresh_ttl'),
                'user' => $user
            ])
            ->withCookie($tokenCookie);
    }

    public function refreshToken(RefreshTokenRequest $request, JWTAuth $auth): JsonResponse
    {
        try {
            $token = $auth->parseToken()->refresh();
            $tokenCookie = $this->makeAuthorizationTokenCookie($token);

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

    public function logout(LogoutRequest $request, JWTAuth $auth): Response
    {
        try {
            $auth->parseToken();
            $auth->invalidate(true);
            $auth->unsetToken();

            $tokenCookie = $this->makeAuthorizationTokenExpiredCookie();

            return response('', Response::HTTP_NO_CONTENT)->withCookie($tokenCookie);
        } catch (JWTException $e) {
            throw new UnauthorizedHttpException('jwt-auth', $e->getMessage(), $e, $e->getCode());
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request, UserService $service): Response
    {
        $service->forgotPassword($request->input('email'));

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function restorePassword(RestorePasswordRequest $request, UserService $service): Response
    {
        $service->restorePassword(
            $request->input('token'),
            $request->input('password')
        );

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function checkRestoreToken(CheckRestoreTokenRequest $request): Response
    {
        return response('', Response::HTTP_NO_CONTENT);
    }

    private function makeAuthorizationTokenExpiredCookie(): Cookie
    {
        return $this->makeAuthorizationTokenCookie(null, false, true);
    }

    private function makeAuthorizationTokenCookie($token, bool $remember = false, $forget = false): Cookie
    {
        $minutes = $forget ? -2628000 : ($remember ? config('jwt.refresh_ttl') : 0);

        return cookie('token', $token, $minutes, null, null, true, true, false, 'None');
    }
}

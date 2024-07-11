<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\CheckRestoreTokenRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LogoutRequest;
use App\Http\Requests\Auth\RefreshTokenRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Requests\Auth\RestorePasswordRequest;
use App\Http\Resources\Auth\SuccessLoginResource;
use App\Http\Resources\Auth\RefreshTokenResource;
use App\Mail\ForgotPasswordMail;
use App\Models\User;
use Illuminate\Http\Response;
use App\Services\UserService;
use App\Traits\TokenTrait;
use Illuminate\Support\Facades\Mail;
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

    public function forgotPassword(ForgotPasswordRequest $request): Response
    {
        Password::sendResetLink($request->only('email'), function ($user, $token) {
            Mail::to($user->email)->send(new ForgotPasswordMail(['hash' => $token]));
        });

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function restorePassword(RestorePasswordRequest $request, UserService $service): Response
    {
        $credentials = $request->only('email', 'password', 'token');
        $credentials['password_confirmation'] = $credentials['password'];
        $status = Password::reset(
            //$request->only('email', 'password', 'token'),
            $credentials,
            function (User $user, string $password) use ($service, $request) {
                /*$user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();*/
                /*$service->restorePassword(
                    $request->input('token'),
                    $password
                );*/

                //event(new PasswordReset($user));
            }
            /*$service->restorePassword(
                $request->input('token'),
                $request->input('password')
            )*/
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw new UnprocessableEntityHttpException(__($status));
        }

        /*return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);

        $service->restorePassword(
            $request->input('token'),
            $request->input('password')
        );*/

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function checkRestoreToken(CheckRestoreTokenRequest $request): Response
    {
        return response('', Response::HTTP_NO_CONTENT);
    }
}

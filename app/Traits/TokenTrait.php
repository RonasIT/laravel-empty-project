<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

trait TokenTrait
{
    private function makeAuthorizationTokenExpiredCookie(): Cookie
    {
        try {
            Auth::guard()->parseToken();
            Auth::guard()->invalidate(true);
            Auth::guard()->unsetToken();

            return $this->makeAuthorizationTokenCookie(null, false, true);
        } catch (JWTException $e) {
            throw new UnauthorizedHttpException('jwt-auth', $e->getMessage(), $e, $e->getCode());
        }
    }

    private function makeAuthorizationTokenCookie($token, bool $remember = false, $forget = false): Cookie
    {
        $minutes = $forget ? -2628000 : ($remember ? config('jwt.refresh_ttl') : 0);

        return cookie(
            name: 'token',
            value: $token,
            minutes: $minutes,
            secure: true,
            sameSite: 'None',
        );
    }
}

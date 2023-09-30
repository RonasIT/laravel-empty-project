<?php

namespace App\Traits;

use Illuminate\Http\Response;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

trait TokenTrait
{
    private function makeAuthorizationTokenExpiredCookie(): Cookie
    {
        try {
            auth()->parseToken();
            auth()->invalidate(true);
            auth()->unsetToken();

            return $this->makeAuthorizationTokenCookie(null, false, true);
        } catch (JWTException $e) {
            throw new UnauthorizedHttpException('jwt-auth', $e->getMessage(), $e, $e->getCode());
        }
    }

    private function makeAuthorizationTokenCookie($token, bool $remember = false, $forget = false): Cookie
    {
        $minutes = $forget ? -2628000 : ($remember ? config('jwt.refresh_ttl') : 0);

        return cookie('token', $token, $minutes, null, null, true, true, false, 'None');
    }
}

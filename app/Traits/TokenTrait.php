<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Cookie;

trait TokenTrait
{
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

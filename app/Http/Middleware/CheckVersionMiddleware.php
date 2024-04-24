<?php

namespace App\Http\Middleware;

use App\Support\Version;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckVersionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $current = Version::current();

        if (in_array($current, config('app.disabled_api_versions'))) {
            abort(Response::HTTP_UPGRADE_REQUIRED);
        }

        return $next($request);
    }
}

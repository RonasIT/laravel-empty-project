<?php

use App\Http\Middleware\ClearVersion;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/status',
        apiPrefix: ''
    )
    ->withMiddleware(function (Middleware $middleware) {
        // TODO: need to check how it works, maybe this row is not necessary
        $middleware->throttleApi('60,1');

        $middleware->group('auth_group', [
            Authenticate::class,
            CheckForMaintenanceMode::class,
        ]);

        $middleware->group('guest_group', [
            CheckForMaintenanceMode::class,
        ]);

        $middleware->alias([
            'clear_version' => ClearVersion::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->dontReport([
            AuthenticationException::class,
            AuthorizationException::class,
            HttpException::class,
            ModelNotFoundException::class,
            TokenMismatchException::class,
            ValidationException::class,
        ]);

        $exceptions->dontFlash([
            'password',
            'password_confirmation',
        ]);
    })->create();

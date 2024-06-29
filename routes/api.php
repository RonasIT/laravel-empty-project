<?php

use App\Enums\VersionEnum;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;

Route::prefix('v{version}')
    ->middleware('clear_version')
    ->group(function () {
        Route::versionFrom(VersionEnum::v0_1)->group(function () {
            Route::middleware('auth_group')->group(function () {
                Route::post('auth/logout', [AuthController::class, 'logout']);

                Route::controller(UserController::class)->group(function () {
                    Route::post('users', 'create');
                    Route::put('users/{id}', 'update');
                    Route::delete('users/{id}', 'delete');
                    Route::get('users/{id}', 'get');
                    Route::get('users', 'search');
                    Route::get('profile', 'profile');
                    Route::put('profile', 'updateProfile');
                    Route::delete('profile', 'deleteProfile');
                });

                Route::controller(SettingController::class)->group(function () {
                    Route::put('settings/{name}', 'update');
                    Route::get('settings/{name}', 'get');
                    Route::get('settings', 'search');
                });
            });

            Route::middleware('guest_group')->group(function () {
                Route::controller(AuthController::class)->group(function () {
                    Route::post('login', 'login');
                    Route::post('register', 'register');
                    Route::get('auth/refresh', 'refreshToken');
                    Route::post('auth/forgot-password', 'forgotPassword');
                    Route::post('auth/restore-password', 'restorePassword');
                    Route::post('auth/token/check', 'checkRestoreToken');
                });
            });
        });
    });

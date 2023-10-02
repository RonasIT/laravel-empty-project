<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth_group'], function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);

    Route::post('users', [UserController::class, 'create']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'delete']);
    Route::get('users/{id}', [UserController::class, 'get']);
    Route::get('users', [UserController::class, 'search']);
    Route::get('profile', [UserController::class, 'profile']);
    Route::put('profile', [UserController::class, 'updateProfile']);
    Route::delete('profile', [UserController::class, 'deleteProfile']);

    Route::post('media', [MediaController::class, 'create']);
    Route::delete('media/{id}', [MediaController::class, 'delete']);

    Route::put('settings/{name}', [SettingController::class, 'update']);
    Route::get('settings/{name}', [SettingController::class, 'get']);
    Route::get('settings', [SettingController::class, 'search']);
});

Route::group(['middleware' => 'guest_group'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::get('auth/refresh', [AuthController::class, 'refreshToken']);
    Route::post('auth/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('auth/restore-password', [AuthController::class, 'restorePassword']);
    Route::post('auth/token/check', [AuthController::class, 'checkRestoreToken']);

    Route::get('media', [MediaController::class, 'search']);

    Route::get('status', [StatusController::class, 'status']);
});

<?php

use App\Http\Controllers\testController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\SettingController;

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

$auth = [
    'middleware' => ['jwt.auth', 'maintenance']
];

$guest = [
    'middleware' => ['maintenance']
];

Route::group($auth, function () {
    Route::post('/users', ['uses' => UserController::class.'@create']);
    Route::put('/users/{id}', ['uses' => UserController::class.'@update']);
    Route::delete('/users/{id}', ['uses' => UserController::class.'@delete']);
    Route::get('/users/{id}', ['uses' => UserController::class.'@get']);
    Route::get('/users', ['uses' => UserController::class.'@search']);
    Route::get('/profile', ['uses' => UserController::class.'@profile']);
    Route::put('/profile', ['uses' => UserController::class.'@updateProfile']);

    Route::post('/media', ['uses' => MediaController::class.'@create']);
    Route::delete('/media/{id}', ['uses' => MediaController::class.'@delete']);
    Route::get('/media', ['uses' => MediaController::class.'@search']);

    Route::post('/settings', ['uses' => SettingController::class.'@create']);
    Route::put('/settings/{key}', ['uses' => SettingController::class.'@update']);
    Route::delete('/settings/{key}', ['uses' => SettingController::class.'@delete']);
    Route::get('/settings/{key}', ['uses' => SettingController::class.'@get']);
    Route::get('/settings', ['uses' => SettingController::class.'@search']);
});

Route::group($guest, function () {
    Route::post('/login', ['uses' => AuthController::class . '@login']);
    Route::get('/auth/refresh', ['uses' => AuthController::class . '@refreshToken'])
        ->middleware(['jwt.refresh']);
    Route::post('/register', ['uses' => AuthController::class . '@register']);
    Route::post('/auth/forgot-password', ['uses' => AuthController::class . '@forgotPassword']);
    Route::post('/auth/restore-password', ['uses' => AuthController::class . '@restorePassword']);
    Route::post('/auth/token/check', ['uses' => AuthController::class . '@checkRestoreToken']);
});

Route::post('/tests', ['uses' => testController::class.'@create'])->middleware('jwt.auth');
Route::put('/tests/{id}', ['uses' => testController::class.'@update'])->middleware('jwt.auth');
Route::delete('/tests/{id}', ['uses' => testController::class.'@delete'])->middleware('jwt.auth');
Route::get('/tests/{id}', ['uses' => testController::class.'@get']);
Route::get('/tests', ['uses' => testController::class.'@search']);
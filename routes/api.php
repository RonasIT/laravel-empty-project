<?php

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

$auth = [
    'middleware' => ['jwt.auth']
];


Route::group($auth, function () use ($auth) {
    Route::post('/users', ['uses' => UserController::class.'@create']);
    Route::put('/users/{id}', ['uses' => UserController::class.'@update']);
    Route::delete('/users/{id}', ['uses' => UserController::class.'@delete']);
    Route::get('/users/{id}', ['uses' => UserController::class.'@get']);
    Route::get('/users', ['uses' => UserController::class.'@search']);
});
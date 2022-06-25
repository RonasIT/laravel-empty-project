<?php

use App\Modules\Media\Http\Controllers\MediaController;

Route::group(['middleware' => 'auth'], function () {
    Route::post('/media', ['uses' => MediaController::class . '@create']);
    Route::delete('/media/{id}', ['uses' => MediaController::class . '@delete']);
    Route::get('/media', ['uses' => MediaController::class . '@search']);
});

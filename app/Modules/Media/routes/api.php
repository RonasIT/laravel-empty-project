<?php

use App\Modules\Media\Http\Controllers\MediaController;

Route::group(['middleware' => 'auth_group'], function () {
    Route::post('/media', [MediaController::class, 'create']);
    Route::delete('/media/{id}', [MediaController::class, 'delete']);
    Route::post('/media/bulk', [MediaController::class, 'bulkCreate']);
});

Route::group(['middleware' => 'guest_group'], function () {
    Route::get('/media', [MediaController::class, 'search']);
});

<?php

use App\Modules\Media\Contracts\Controllers\MediaControllerContract;

Route::group(['middleware' => 'auth'], function () {
    Route::post('/media', ['uses' => MediaControllerContract::class . '@create']);
    Route::delete('/media/{id}', ['uses' => MediaControllerContract::class . '@delete']);
    Route::get('/media', ['uses' => MediaControllerContract::class . '@search']);
    Route::post('/media/bulk', ['uses' => MediaControllerContract::class . '@bulkCreate']);
});

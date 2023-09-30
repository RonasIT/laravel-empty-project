<?php

use App\Modules\Media\Contracts\Controllers\MediaControllerContract;

Route::group(['middleware' => 'auth_group'], function () {
    Route::post('/media', [MediaControllerContract::class, 'create']);
    Route::delete('/media/{id}', [MediaControllerContract::class, 'delete']);
    Route::get('/media', [MediaControllerContract::class, 'search']);
    Route::post('/media/bulk', [MediaControllerContract::class, 'bulkCreate']);
});

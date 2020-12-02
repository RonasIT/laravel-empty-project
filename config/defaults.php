<?php

return [
    'items_per_page' => 10,
    'permitted_media_types' => ['jpg', 'jpeg', 'bmp', 'png'],

    /*
    |--------------------------------------------------------------------------
    | Password hash lifetime, hours
    |--------------------------------------------------------------------------
    |
    | Here you can set how often "set_password_hash" field of "users" table will be clearing.
    |
    */

    'password_hash_lifetime' => env('PASSWORD_HASH_LIFETIME', 1)
];

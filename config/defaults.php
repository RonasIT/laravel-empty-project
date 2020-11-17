<?php

return [
    'items_per_page' => 10,
    'permitted_media_types' => ['jpeg', 'bmp', 'png'],

    /*
    |--------------------------------------------------------------------------
    | Password hash lifetime
    |--------------------------------------------------------------------------
    |
    | Here you can set when you should clear set password hash.
    | Value can be set in hours.
    |
    */

    'password_hash_lifetime' => env('PASSWORD_HASH_LIFETIME', 1)
];

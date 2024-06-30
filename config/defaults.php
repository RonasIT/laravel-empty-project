<?php

return [
    'items_per_page' => 10,

    /*
    |--------------------------------------------------------------------------
    | Password hash lifetime, hours
    |--------------------------------------------------------------------------
    |
    | Here you can set how often "set_password_hash" field of "users" table will be clearing.
    |
    */
    // TODO: maybe we don't need it anymore
    'password_hash_lifetime' => env('PASSWORD_HASH_LIFETIME', 1),
];

<?php

return [
    'items_per_page' => 10,
    'permitted_media_types' => ['jpeg', 'bmp', 'png'],
    'password_hash_lifetime' => env('PASSWORD_HASH_LIFETIME')
];

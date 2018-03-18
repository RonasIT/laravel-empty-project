<?php

namespace App\Models;

class Option extends Model
{
    protected $fillable = [
        'key', 'value'
    ];

    protected $hidden = ['pivot'];

    protected $casts = [
        'value' => 'array'
    ];
}

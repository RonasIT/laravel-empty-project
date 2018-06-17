<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

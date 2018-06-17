<?php

namespace App\Models;

use RonasIT\Support\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use ModelTrait;

    protected $fillable = [
        'key', 'value'
    ];

    protected $hidden = ['pivot'];

    protected $casts = [
        'value' => 'array'
    ];
}

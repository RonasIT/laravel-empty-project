<?php

namespace App\Models;

use RonasIT\Support\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Model;

class test extends Model
{
    use ModelTrait;

    protected $fillable = [
        'votes',
        'name',
        '',
    ];

    protected $hidden = ['pivot'];
}
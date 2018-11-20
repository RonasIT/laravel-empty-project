<?php

namespace App\Models;

use RonasIT\Support\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use ModelTrait;

    protected $fillable = [
        'name',
    ];

    protected $hidden = ['pivot'];
}
<?php

namespace App\Models;

use RonasIT\Support\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use ModelTrait;

    const ADMIN = 1;
    const USER = 2;
    
    protected $fillable = [
        'name',
    ];

    protected $hidden = ['pivot'];
}
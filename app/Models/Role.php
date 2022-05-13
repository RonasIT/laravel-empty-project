<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use RonasIT\Support\Traits\ModelTrait;

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

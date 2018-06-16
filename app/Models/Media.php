<?php

namespace App\Models;

use RonasIT\Support\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use ModelTrait;

    protected $fillable = [
        'link',
        'name',
    ];

    protected $hidden = ['pivot'];
}
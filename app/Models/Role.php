<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RonasIT\Support\Traits\ModelTrait;

class Role extends Model
{
    use ModelTrait;
    use HasFactory;

    public const ADMIN = 1;
    public const USER = 2;

    protected $fillable = [
        'name',
    ];

    protected $hidden = ['pivot'];
}

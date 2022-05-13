<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use RonasIT\Support\Traits\ModelTrait;
use Tymon\JWTAuth\Facades\JWTAuth;

class Setting extends Model
{
    use ModelTrait;

    public $incrementing = false;

    protected $primaryKey = 'name';
    protected $fillable = [
        'name',
        'value',
        'is_public'
    ];
    protected $hidden = ['pivot'];

    protected $casts = [
        'value' => 'array',
        'name' => 'string',
        'is_public' => 'boolean'
    ];


    public function scopeApplySettingPermissionRestrictions($query)
    {
        $user = JWTAuth::toUser();

        if ($user->role_id !== Role::ADMIN) {
            $query->where('is_public', true);
        }
    }
}

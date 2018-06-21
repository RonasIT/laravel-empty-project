<?php

namespace App\Models;

use App\Repositories\RoleRepository;
use RonasIT\Support\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Facades\JWTAuth;

class Setting extends Model
{
    use ModelTrait;

    protected $fillable = [
        'key', 'value', 'is_public'
    ];

    protected $hidden = ['pivot'];

    protected $casts = [
        'value' => 'array'
    ];


    public function scopeApplySettingPermissionRestrictions($query)
    {
        $user = JWTAuth::toUser();

        if ($user->role_id !== RoleRepository::ADMIN_ROLE) {
            $query->where(function ($query) use ($user) {
                $query->where('is_public', true);
            });
        }
    }
}

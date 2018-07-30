<?php

namespace App\Models;

use App\Repositories\RoleRepository;
use RonasIT\Support\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Facades\JWTAuth;

class Media extends Model
{
    use ModelTrait;

    protected $fillable = [
        'link',
        'name',
        'owner_id',
        'is_public'
    ];

    protected $casts = [
        'is_public' => 'boolean'
    ];

    protected $hidden = ['pivot'];

    public function scopeApplyMediaPermissionRestrictions($query)
    {
        if (!JWTAuth::getToken()) {
            $query->where('is_public', true);

            return;
        }

        $user = JWTAuth::toUser();

        if ($user->role_id !== RoleRepository::ADMIN_ROLE) {
            $query->where(function ($query) use ($user) {
                $query->where('is_public', true)
                    ->orWhere('owner_id', $user->id);
            });
        }
    }
}
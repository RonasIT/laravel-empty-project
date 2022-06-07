<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RonasIT\Support\Traits\ModelTrait;
use Tymon\JWTAuth\Facades\JWTAuth;

class Media extends Model
{
    use ModelTrait;
    use SoftDeletes;

    protected $fillable = [
        'link',
        'name',
        'owner_id',
        'is_public'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'deleted_at' => 'date'
    ];

    protected $hidden = ['pivot'];

    public function scopeApplyMediaPermissionRestrictions($query)
    {
        if (!JWTAuth::getToken()) {
            $query->where('is_public', true);

            return;
        }

        $user = JWTAuth::toUser();

        if ($user->role_id !== Role::ADMIN) {
            $query->where(function ($subQuery) use ($user) {
                $subQuery
                    ->where('is_public', true)
                    ->orWhere('owner_id', $user->id);
            });
        }
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }
}

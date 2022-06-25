<?php

namespace App\Modules\Media\Models;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RonasIT\Support\Traits\ModelTrait;
use Tymon\JWTAuth\Facades\JWTAuth;

class Media extends Model
{
    use ModelTrait, SoftDeletes;

    protected $fillable = [
        'link',
        'name',
        'owner_id',
        'is_public'
    ];

    protected $casts = [
        'is_public' => 'boolean'
    ];

    protected $dates = [
        'deleted_at'
    ];

    protected $hidden = ['pivot'];

    public function scopeApplyMediaPermissionRestrictions($query): void
    {
        if (!JWTAuth::getToken()) {
            $query->where('is_public', true);

            return;
        }

        /** @var User $user */
        $user = JWTAuth::toUser();

        if ($user->role_id !== Role::ADMIN) {
            $query->where(function ($subQuery) use ($user) {
                $subQuery
                    ->where('is_public', true)
                    ->orWhere('owner_id', $user->id);
            });
        }
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

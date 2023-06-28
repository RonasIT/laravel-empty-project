<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use RonasIT\Support\Traits\ModelTrait;

class Media extends Model
{
    use ModelTrait;
    use SoftDeletes;
    use HasFactory;

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

    public function scopeApplyMediaPermissionRestrictions(Builder $query): void
    {
        if (!Auth::check()) {
            $query->where('is_public', true);

            return;
        }

        $user = Auth::user();

        if (!$user->isAdmin()) {
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

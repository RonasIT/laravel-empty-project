<?php

namespace App\Models;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use RonasIT\Support\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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

    public function scopeApplySettingPermissionRestrictions(Builder $query): void
    {
        $user = Auth::user();

        if (Arr::get($user, 'role_id') !== Role::ADMIN) {
            $query->where('is_public', true);
        }
    }
}

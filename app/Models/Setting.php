<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use RonasIT\Support\Traits\ModelTrait;

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

        if ($user && !$user->isAdmin()) {
            $query->where('is_public', true);
        }
    }
}

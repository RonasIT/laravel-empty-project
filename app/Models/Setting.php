<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use RonasIT\Support\Traits\ModelTrait;

class Setting extends Model
{
    use ModelTrait;

    public $incrementing = false;

    protected $primaryKey = 'name';
    protected $fillable = [
        'name',
        'value',
        'is_public',
    ];
    protected $hidden = ['pivot'];

    public function scopeApplySettingPermissionRestrictions(Builder $query): void
    {
        $query->where('is_public', true);
    }

    protected function casts(): array
    {
        return [
            'value' => 'array',
            'name' => 'string',
            'is_public' => 'boolean',
        ];
    }
}

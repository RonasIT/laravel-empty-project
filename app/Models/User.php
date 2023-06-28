<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use RonasIT\Support\Traits\ModelTrait;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use ModelTrait;
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'set_password_hash_created_at'
    ];

    protected $guarded = [
        'set_password_hash'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'set_password_hash'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'set_password_hash_created_at' => 'datetime'
    ];

    public function getJWTIdentifier(): int
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class, 'owner_id');
    }

    public function isAdmin(): bool
    {
        return $this->role_id === Role::ADMIN;
    }

    public function isUser(): bool
    {
        return $this->role_id === Role::USER;
    }
}

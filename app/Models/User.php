<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use RonasIT\Support\Traits\ModelTrait;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, ModelTrait;

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
//        'set_password_hash',
//        'set_password_hash_created_at'
    ];

    protected $dates = [
        'set_password_hash_created_at'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}

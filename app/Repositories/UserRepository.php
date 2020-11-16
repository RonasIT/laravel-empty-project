<?php

namespace App\Repositories;

use App\Models\User;
use Carbon\Carbon;
use RonasIT\Support\Repositories\BaseRepository;

/**
 * @property  User $model
*/
class UserRepository extends BaseRepository
{
    public function __construct()
    {
        $this->setModel(User::class);
    }

    public function updateResetPasswordHashToNull()
    {
        return $this->getQuery([])
            ->where('password_hash_created_at', '>', Carbon::now()->addMinutes(config('defaults.password_hash_lifetime')))
            ->update(['set_password_hash' => null]);
    }
}

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

    public function clearSetPasswordHash(): int
    {
        return $this
            ->getQuery()
            ->where('set_password_hash_created_at', '<', Carbon::now()->subHours(config('defaults.password_hash_lifetime')))
            ->update([
                'set_password_hash' => null,
                'set_password_hash_created_at' => null
            ]);
    }
}

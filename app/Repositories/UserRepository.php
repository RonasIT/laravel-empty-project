<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Arr;
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
        return $this->getQuery([])->update(['reset_password_hash' => null]);
    }
}

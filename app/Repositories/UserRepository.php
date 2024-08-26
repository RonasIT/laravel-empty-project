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
}

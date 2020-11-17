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

    public function getNotEmptyHashes()
    {
        return $this
            ->getQuery()
            ->where('set_password_hash_created_at', '!=', null)
            ->where('set_password_hash', '!=', null)
            ->get()
            ->toArray();
    }

    public function clearSetPasswordHash($id)
    {
        return $this
            ->getQuery(['id' => $id])
            ->update(['set_password_hash' => null]);
    }
}

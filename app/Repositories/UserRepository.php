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

    public function create($data)
    {
        $user = User::create(Arr::only($data, User::getFields()));

        return $user->toArray();
    }

    public function search($filters)
    {
        return $this
            ->searchQuery($filters)
            ->filterBy('role_id')
            ->filterByQuery(['name', 'email'])
            ->getSearchResults();
    }
}

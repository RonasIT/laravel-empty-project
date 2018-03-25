<?php

namespace App\Repositories;

use RonasIT\Support\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
        $data['role_id'] = array_get($data, 'role_id', RoleRepository::USER_ROLE);
        $data['password'] = Hash::make($data['password']);

        $user = User::create(array_only($data, User::getFields()));

        return $user->toArray();
    }

    public function search($filters)
    {
        return $this->searchQuery($filters)
            ->filterBy('role_id')
            ->filterByQuery(['name', 'email'])
            ->getSearchResults();
    }

    public function forceUpdate($where, $data) {
        $user = User::where($where)->first();

        $user->forceFill($data);

        $user->save();

        return $user->toArray();
    }
}
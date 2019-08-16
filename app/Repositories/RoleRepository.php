<?php

namespace App\Repositories;

use App\Models\Role;
use RonasIT\Support\Repositories\BaseRepository;

/**
 * @property  Role $model
*/
class RoleRepository extends BaseRepository
{
    public function __construct()
    {
        $this->setModel(Role::class);
    }

    public function search($filters)
    {
        return $this
            ->searchQuery($filters)
            ->filterByQuery(['name'])
            ->getSearchResults();
    }
}

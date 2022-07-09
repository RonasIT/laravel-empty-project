<?php

namespace App\Services;

use App\Repositories\RoleRepository;
use RonasIT\Support\Services\EntityService;

/**
 * @property RoleRepository $repository
 * @mixin RoleRepository
 */
class RoleService extends EntityService
{
    public function __construct()
    {
        $this->setRepository(RoleRepository::class);
    }

    public function search(array $filters)
    {
        return $this->repository
            ->searchQuery($filters)
            ->filterByQuery(['name'])
            ->getSearchResults();
    }
}

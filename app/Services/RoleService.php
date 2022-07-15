<?php

namespace App\Services;

use App\Repositories\RoleRepository;
use Illuminate\Pagination\LengthAwarePaginator;
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

    public function search(array $filters): LengthAwarePaginator
    {
        return $this
            ->searchQuery($filters)
            ->filterByQuery(['name'])
            ->getSearchResults();
    }
}

<?php

namespace App\Services;

use App\Repositories\RoleRepository;
use RonasIT\Support\Services\EntityService;

/**
 * @property  RoleRepository $repository
 */
class RoleService extends EntityService
{
    public function __construct()
    {
        $this->setRepository(RoleRepository::class);
    }
}
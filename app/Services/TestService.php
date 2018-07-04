<?php

namespace App\Services;

use App\Repositories\TestRepository;
use RonasIT\Support\Services\EntityService;

/**
 * @property  TestRepository $repository
 */
class TestService extends EntityService
{
    public function __construct()
    {
        $this->setRepository(TestRepository::class);
    }
}
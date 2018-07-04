<?php

namespace App\Services;

use App\Repositories\testRepository;
use RonasIT\Support\Services\EntityService;

/**
 * @property  testRepository $repository
 */
class testService extends EntityService
{
    public function __construct()
    {
        $this->setRepository(testRepository::class);
    }
}
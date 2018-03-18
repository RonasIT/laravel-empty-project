<?php

namespace App\Services;

use App\Repositories\OptionRepository;
use RonasIT\Support\Services\EntityService;

/**
 * @property  OptionRepository $repository
 */
class OptionService extends EntityService
{
    public function __construct()
    {
        $this->setRepository(OptionRepository::class);
    }
}
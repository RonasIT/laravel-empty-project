<?php

namespace App\Repositories;

use RonasIT\Support\Repositories\BaseRepository;
use App\Models\Test;

/**
 * @property  Test $model
*/
class TestRepository extends BaseRepository
{
    public function __construct()
    {
        $this->setModel(Test::class);
    }

    public function search($filters)
    {
        return $this->searchQuery($filters)
            ->filterByQuery(['name'])
            ->getSearchResults();
    }
}
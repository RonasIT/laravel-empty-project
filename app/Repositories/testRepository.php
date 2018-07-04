<?php

namespace App\Repositories;

use RonasIT\Support\Repositories\BaseRepository;
use App\Models\test;

/**
 * @property  test $model
*/
class testRepository extends BaseRepository
{
    public function __construct()
    {
        $this->setModel(test::class);
    }

    public function search($filters)
    {
        return $this->searchQuery($filters)
            ->filterByQuery(['name'])
            ->getSearchResults();
    }
}
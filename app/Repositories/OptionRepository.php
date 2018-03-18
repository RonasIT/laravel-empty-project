<?php

namespace App\Repositories;

use App\Models\Option;

/**
 * @property  Option $model
*/
class OptionRepository extends Repository
{
    public function __construct()
    {
        $this->setModel(Option::class);
    }

    public function search($filters)
    {
        return $this->searchQuery($filters)
            ->filterByQuery(['key'])
            ->setOrderBy()
            ->getSearchResults();
    }

    public function update($where, $data)
    {
        $model = $this->model;

        $entity = $model::where($where)->first();

        $entity->fill($data);

        $entity->save();

        return $entity->toArray();
    }
}

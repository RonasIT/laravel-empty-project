<?php

namespace App\Repositories;

use App\Models\Setting;
use RonasIT\Support\Repositories\BaseRepository;

/**
 * @property  Setting $model
*/
class SettingRepository extends BaseRepository
{
    public function __construct()
    {
        $this->setModel(Setting::class);
    }

    public function search($filters)
    {
        return $this->searchQuery($filters)
            ->filterByQuery(['key'])
            ->getSearchResults();
    }

    protected function getSearchResults()
    {
        $this->query->applySettingPermissionRestrictions();

        return parent::getSearchResults();
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

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
        return $this
            ->searchQuery($filters)
            ->filterByQuery(['name'])
            ->orderBy('name')
            ->getSearchResults();
    }

    public function getSearchResults()
    {
        $this->query->applySettingPermissionRestrictions();

        return parent::getSearchResults();
    }
}

<?php

namespace App\Services;

use App\Repositories\SettingRepository;
use RonasIT\Support\Services\EntityService;

/**
 * @property  SettingRepository $repository
 */
class SettingService extends EntityService
{
    public function __construct()
    {
        $this->setRepository(SettingRepository::class);
    }

    public function get($key, $default = null)
    {
        $explodedKey = explode('.', $key);
        $primaryKey = array_shift($explodedKey);

        $setting = $this->repository->findBy('name', $primaryKey);

        if (empty($setting)) {
            return $default;
        }

        if (empty($explodedKey)) {
            return $setting['value'];
        }

        array_unshift($explodedKey, 'value');
        $valuePath = implode('.', $explodedKey);

        return array_get($setting, $valuePath);
    }

    public function set($key, $value)
    {
        $explodedKey = explode('.', $key);
        $primaryKey = array_shift($explodedKey);

        $setting = $this->repository->findBy('name', $primaryKey);

        if (empty($setting)) {
            return $this->repository->create([
                'name' => $key,
                'value' => $value
            ]);
        }

        array_unshift($explodedKey, 'value');
        $valuePath = implode('.', $explodedKey);

        array_set($setting, $valuePath, $value);

        return $this->repository->update(['name' => $primaryKey], [
            'value' => $setting['value']
        ]);
    }
}
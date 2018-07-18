<?php

namespace App\Services;

use App\Repositories\MediaRepository;
use RonasIT\Support\Services\EntityService;
use RonasIT\Support\Traits\FilesTrait;

/**
 * @property  MediaRepository $repository
 */
class MediaService extends EntityService
{
    use FilesTrait;

    public function __construct()
    {
        $this->setRepository(MediaRepository::class);
    }

    public function create($content, $fileName, $data = [])
    {
        $url = $this->saveFile($fileName, $content, true);
        $data['link'] = str_replace(config('app.url'), '', $url);

        return $this->repository->create($data);
    }
}
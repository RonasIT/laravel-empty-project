<?php

namespace App\Services;

use App\Repositories\MediaRepository;
use Illuminate\Support\Facades\Auth;
use RonasIT\Support\Repositories\BaseRepository;
use RonasIT\Support\Services\EntityService;
use RonasIT\Support\Traits\FilesUploadTrait;

/**
 * @property MediaRepository $repository
 * @mixin MediaRepository
 */
class MediaService extends EntityService
{
    use FilesUploadTrait;

    public function __construct()
    {
        $this->setRepository(MediaRepository::class);
    }

    public function search($filters)
    {
        return $this->repository
            ->searchQuery($filters)
            ->filterByQuery(['name'])
            ->getSearchResults();
    }

    public function create($content, $fileName, $data = [])
    {
        $url = $this->saveFile($fileName, $content, true);
        $data['link'] = str_replace(config('app.url'), '', $url);
        $data['name'] = $fileName;
        $data['owner_id'] = Auth::user()->id;

        return $this->repository->create($data);
    }
}

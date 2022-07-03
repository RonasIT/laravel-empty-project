<?php

namespace App\Modules\Media\Services;

use App\Modules\Media\Repositories\MediaRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
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

    public function search($filters): LengthAwarePaginator
    {
        return $this->repository
            ->searchQuery($filters)
            ->filterByQuery(['name'])
            ->getSearchResults();
    }

    public function create($content, $fileName, $data = []): Model
    {
        $url = $this->saveFile($fileName, $content, true);
        $data['link'] = str_replace(config('app.url'), '', $url);
        $data['name'] = $fileName;
        $data['owner_id'] = Auth::user()->id;

        return $this->repository->create($data);
    }

    public function bulkCreate(array $data): array
    {
        $result = [];

        foreach ($data as $media) {
            /** @var UploadedFile $file */
            $file = $media['file'];
            $content = file_get_contents($file->getPathname());

            $result[] = $this->create($content, $file->getClientOriginalName(), $media);
        }

        return $result;
    }
}

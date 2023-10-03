<?php

namespace App\Modules\Media\Services;

use App\Modules\Media\Contracts\Services\MediaServiceContract;
use App\Modules\Media\Repositories\MediaRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RonasIT\Support\Services\EntityService;
use RonasIT\Support\Traits\FilesUploadTrait;

/**
 * @property MediaRepository $repository
 * @mixin MediaRepository
 */
class MediaService extends EntityService implements MediaServiceContract
{
    use FilesUploadTrait;

    public function __construct()
    {
        $this->setRepository(MediaRepository::class);
    }

    public function search(array $filters): LengthAwarePaginator
    {
        return $this
            ->repository
            ->searchQuery($filters)
            ->filterByQuery(['name'])
            ->getSearchResults();
    }

    public function create($content, string $fileName, array $data = []): Model
    {
        $fileName = $this->saveFile($fileName, $content);
        $data['name'] = $fileName;
        $data['link'] = Storage::url($data['name']);
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

    public function delete($where): int
    {
        return $this->repository->delete($where);
    }
}

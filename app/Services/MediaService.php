<?php

namespace App\Services;

use App\Repositories\MediaRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
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
        $data['link'] = $this->saveFile($fileName, $content, true);

        return $this->repository->create($data);
    }

    public function saveFile($name, $content, $returnUrl = false)
    {
        $preparedName = $this->prepareName($name);

        Storage::put($preparedName, $content);

        if (!$returnUrl) {
            return Storage::path($preparedName);
        }

        $url = Storage::url($preparedName);

        return str_replace(config('app.url'), '', $url);
    }

    protected function prepareName($name)
    {
        $explodedName = explode('.', $name);
        $extension = array_pop($explodedName);
        $name = implode('_', $explodedName);
        $timestamp = Carbon::now()->timestamp;
        $hash = md5($name);

        return "public/{$hash}_{$timestamp}.{$extension}";
    }
}
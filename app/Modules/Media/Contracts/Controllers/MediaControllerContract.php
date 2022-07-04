<?php

namespace App\Modules\Media\Contracts\Controllers;

use App\Modules\Media\Contracts\Requests\BulkCreateMediaRequestContract;
use App\Modules\Media\Contracts\Requests\CreateMediaRequestContract;
use App\Modules\Media\Contracts\Requests\DeleteMediaRequestContract;
use App\Modules\Media\Contracts\Requests\SearchMediaRequestContract;
use App\Modules\Media\Contracts\Services\MediaServiceContract;
use Illuminate\Http\JsonResponse;

interface MediaControllerContract
{
    public function create(CreateMediaRequestContract $request, MediaServiceContract $mediaService): JsonResponse;

    public function bulkCreate(BulkCreateMediaRequestContract $request, MediaServiceContract $mediaService): JsonResponse;

    public function search(SearchMediaRequestContract $request, MediaServiceContract $mediaService): JsonResponse;

    public function delete(DeleteMediaRequestContract $request, MediaServiceContract $mediaService, int $id);
}
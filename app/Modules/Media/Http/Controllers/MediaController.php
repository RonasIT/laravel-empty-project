<?php

namespace App\Modules\Media\Http\Controllers;

use App\Modules\Media\Contracts\Requests\BulkCreateMediaRequestContract;
use App\Modules\Media\Contracts\Requests\CreateMediaRequestContract;
use App\Modules\Media\Contracts\Requests\DeleteMediaRequestContract;
use App\Modules\Media\Contracts\Requests\SearchMediaRequestContract;
use App\Modules\Media\Contracts\Resources\MediaCollectionContract;
use App\Modules\Media\Contracts\Resources\MediaListResourceContract;
use App\Modules\Media\Contracts\Resources\MediaResourceContract;
use App\Modules\Media\Contracts\Services\MediaServiceContract;
use App\Modules\Media\Http\Resources\MediaCollection;
use App\Modules\Media\Http\Resources\MediaListResource;
use App\Modules\Media\Http\Resources\MediaResource;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

use function response;

class MediaController extends Controller
{
    public function create(
        CreateMediaRequestContract $request,
        MediaServiceContract $mediaService
    ): MediaResourceContract {
        $file = $request->file('file');
        $data = $request->onlyValidated();

        $content = file_get_contents($file->getPathname());

        $media = $mediaService->create($content, $file->getClientOriginalName(), $data);

        return new MediaResource($media);
    }

    public function delete(DeleteMediaRequestContract $request, MediaServiceContract $mediaService, int $id): Response
    {
        $mediaService->delete($id);

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function search(
        SearchMediaRequestContract $request,
        MediaServiceContract $mediaService
    ): MediaCollectionContract {
        $result = $mediaService->search($request->onlyValidated());

        return MediaCollection::make($result);
    }

    public function bulkCreate(
        BulkCreateMediaRequestContract $request,
        MediaServiceContract $mediaService
    ): MediaListResourceContract {
        $result = $mediaService->bulkCreate($request->onlyValidated('media'));

        return MediaListResource::make($result);
    }
}

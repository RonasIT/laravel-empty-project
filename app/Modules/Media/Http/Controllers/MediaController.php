<?php

namespace App\Modules\Media\Http\Controllers;

use App\Modules\Media\Http\Requests\CreateMediaBulkRequest;
use App\Modules\Media\Http\Requests\CreateMediaRequest;
use App\Modules\Media\Http\Requests\DeleteMediaRequest;
use App\Modules\Media\Http\Requests\SearchMediaRequest;
use App\Modules\Media\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use function response;

class MediaController extends Controller
{
    public function create(CreateMediaRequest $request, MediaService $service): JsonResponse
    {
        $file = $request->file('file');
        $data = $request->onlyValidated();

        $content = file_get_contents($file->getPathname());

        $media = $service->create($content, $file->getClientOriginalName(), $data);

        return response()->json($media);
    }

    public function delete(DeleteMediaRequest $request, MediaService $service, $id)
    {
        $service->delete($id);

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function search(SearchMediaRequest $request, MediaService $service): JsonResponse
    {
        $result = $service->search($request->onlyValidated());

        return response()->json($result);
    }

    public function bulkCreate(CreateMediaBulkRequest $request, MediaService $mediaService): JsonResponse
    {
        $result = $mediaService->bulkCreate($request->onlyValidated('media'));

        return response()->json($result);
    }
}

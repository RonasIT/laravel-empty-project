<?php

namespace App\Http\Controllers;

use App\Http\Requests\Media\CreateMediaRequest;
use App\Http\Requests\Media\DeleteMediaRequest;
use App\Http\Requests\Media\SearchMediaRequest;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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

    public function delete(DeleteMediaRequest $request, MediaService $service, int $id): Response
    {
        $service->delete($id);

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function search(SearchMediaRequest $request, MediaService $service): JsonResponse
    {
        $result = $service->search($request->onlyValidated());

        return response()->json($result);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Media\CreateMediaRequest;
use App\Http\Requests\Media\CreateMultipartMediaRequest;
use App\Http\Requests\Media\GetMediaRequest;
use App\Http\Requests\Media\UpdateMediaRequest;
use App\Http\Requests\Media\DeleteMediaRequest;
use App\Http\Requests\Media\SearchMediaRequest;
use App\Services\MediaService;
use Symfony\Component\HttpFoundation\Response;

class MediaController extends Controller
{
    public function create(CreateMediaRequest $request, MediaService $service) {
        $file = $request->file('file');
        $data = $request->all();

        $content = file_get_contents($file->getPathname());

        $media = $service->create($content, $file->getClientOriginalName(), $data);

        return response()->json($media);
    }

    public function get(GetMediaRequest $request, MediaService $service, $id) {
        $result = $service->first(['id' => $id]);

        return response()->json($result);
    }

    public function delete(DeleteMediaRequest $request, MediaService $service, $id) {
        $service->delete(['id' => $id]);

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function search(SearchMediaRequest $request, MediaService $service) {
        $result = $service->search($request->all());

        return response($result);
    }
}
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
    public function create(CreateMediaRequest $request, MediaService $service, $fileName) {
        $data = $request->all();

        $result = $service->create($request->getContent(), $fileName, $data);

        return response()->json($result);
    }

    public function get(GetMediaRequest $request, MediaService $service, $id) {
        $result = $service->first(['id' => $id]);

        return response()->json($result);
    }

    public function update(UpdateMediaRequest $request, MediaService $service, $id) {
        $service->update(
            ['id' => $id],
            $request->all()
        );

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function delete(DeleteMediaRequest $request, MediaService $service, $id) {
        $service->delete(['id' => $id]);

        return response('', Response::HTTP_NO_CONTENT);
    }

    public function search(SearchMediaRequest $request, MediaService $service) {
        $result = $service->search($request->all());

        return response($result);
    }

    public function createMultipart(CreateMultipartMediaRequest $request, MediaService $service)
    {
        $file = $request->file('file');

        $content = file_get_contents($file->getPathname());

        $media = $service->create($content, $file->getClientOriginalName(), ['name' => '']);

        return response()->json($media);
    }
}
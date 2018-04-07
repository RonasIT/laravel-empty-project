<?php

namespace App\Http\Requests\Media;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\MediaService;

class GetMediaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    public function validateResolved()
    {
        parent::validateResolved();

        $service = app(MediaService::class);

        if (!$service->exists(['id' => $this->route('id')])) {
            throw new NotFoundHttpException('Media does not exists');
        }
    }
}
<?php

namespace App\Http\Requests\Media;

use App\Http\Requests\Request;
use App\Services\MediaService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteMediaRequest extends Request
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function validateResolved(): void
    {
        parent::validateResolved();

        $service = app(MediaService::class);

        if (!$service->exists($this->route('id'))) {
            throw new NotFoundHttpException(__('validation.exceptions.not_found', ['entity' => 'Media']));
        }
    }
}

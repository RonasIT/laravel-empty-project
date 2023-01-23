<?php

namespace App\Http\Requests\Media;

use App\Http\Requests\Request;
use App\Models\Role;
use App\Services\MediaService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteMediaRequest extends Request
{
    public function authorize(): bool
    {
        return $this->user()->role_id === Role::ADMIN;
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

<?php

namespace App\Http\Requests\Media;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\MediaService;
use App\Repositories\RoleRepository;

class DeleteMediaRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->role_id == RoleRepository::ADMIN_ROLE;
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
            throw new NotFoundHttpException(__('validation.exceptions.not_found', ['entity' => 'Media']));
        }
    }
}
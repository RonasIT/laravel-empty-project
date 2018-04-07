<?php

namespace App\Http\Requests\Media;

use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\RoleRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\MediaService;

class UpdateMediaRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->id == RoleRepository::ADMIN_ROLE;
    }

    public function rules()
    {
        return [
            'name' => 'string'
        ];
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
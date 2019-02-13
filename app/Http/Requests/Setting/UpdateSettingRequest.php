<?php

namespace App\Http\Requests\Setting;

use App\Repositories\RoleRepository;
use App\Services\SettingService;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateSettingRequest extends FormRequest
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

        $service = app(SettingService::class);

        if (!$service->exists(['name' => $this->route('name')])) {
            throw new NotFoundHttpException(__('validation.exceptions.not_found', ['entity' => 'Setting']));
        }
    }
}

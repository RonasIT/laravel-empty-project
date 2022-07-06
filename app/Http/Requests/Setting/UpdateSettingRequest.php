<?php

namespace App\Http\Requests\Setting;

use App\Http\Requests\Request;
use App\Models\Role;
use App\Services\SettingService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateSettingRequest extends Request
{
    public function authorize(): bool
    {
        return $this->user()->role_id == Role::ADMIN;
    }

    public function validateResolved(): void
    {
        parent::validateResolved();

        $service = app(SettingService::class);

        if (!$service->exists(['name' => $this->route('name')])) {
            throw new NotFoundHttpException(__('validation.exceptions.not_found', ['entity' => 'Setting']));
        }
    }
}

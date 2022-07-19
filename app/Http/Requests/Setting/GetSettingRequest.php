<?php

namespace App\Http\Requests\Setting;

use App\Http\Requests\Request;
use App\Models\Role;
use App\Models\Setting;
use Illuminate\Support\Arr;
use App\Services\SettingService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetSettingRequest extends Request
{
    protected ?Setting $setting;

    public function authorize(): bool
    {
        $service = app(SettingService::class);
        $this->setting = $service->findBy('name', $this->route('name'));

        if ($this->user()->role_id === Role::ADMIN) {
            return true;
        }

        return Arr::get($this->setting, 'is_public');
    }

    public function validateResolved(): void
    {
        parent::validateResolved();

        if (empty($this->setting)) {
            throw new NotFoundHttpException(__('validation.exceptions.not_found', ['entity' => 'Setting']));
        }
    }
}

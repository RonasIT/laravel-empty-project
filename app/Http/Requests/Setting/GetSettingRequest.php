<?php

namespace App\Http\Requests\Setting;

use App\Repositories\RoleRepository;
use App\Services\SettingService;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetSettingRequest extends FormRequest
{
    protected $setting;

    public function authorize()
    {
        $service = app(SettingService::class);
        $this->setting = $service->findBy('name', $this->route('name'));

        if ($this->user()->role_id == RoleRepository::ADMIN_ROLE) {
            return true;
        }

        return array_get($this->setting, 'is_public');
    }

    public function rules()
    {
        return [];
    }

    public function validateResolved()
    {
        parent::validateResolved();

        if (empty($this->setting)) {
            throw new NotFoundHttpException(__('validation.exceptions.not_found', ['entity' => 'Setting']));
        }
    }
}

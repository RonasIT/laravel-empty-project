<?php

namespace App\Http\Requests\Setting;

use App\Http\Requests\Request;
use App\Models\Role;
use Illuminate\Support\Arr;
use App\Services\SettingService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetSettingRequest extends Request
{
    protected $setting;

    public function authorize()
    {
        $service = app(SettingService::class);
        $this->setting = $service->findBy('name', $this->route('name'));

        if ($this->user()->role_id == Role::ADMIN) {
            return true;
        }

        return Arr::get($this->setting, 'is_public');
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

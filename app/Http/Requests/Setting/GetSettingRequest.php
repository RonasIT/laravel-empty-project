<?php

namespace App\Http\Requests\Setting;

use App\Repositories\RoleRepository;
use App\Services\SettingService;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return  bool
     */
    public function authorize()
    {
        if ($this->user()->role_id == RoleRepository::ADMIN_ROLE) {
            return true;
        }

        $service = app(SettingService::class);
        $setting = $service->findBy('key', $this->route('key'));

        if (!empty($setting) && $setting['is_public']) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return  array
     */
    public function rules()
    {
        return [];
    }

    public function validateResolved()
    {
        parent::validateResolved();

        $service = app(SettingService::class);

        if (!$service->exists(['key' => $this->route('key')])) {
            throw new NotFoundHttpException('Setting does not exists');
        }
    }
}

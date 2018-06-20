<?php

namespace App\Http\Requests\Setting;

use App\Repositories\RoleRepository;
use App\Services\SettingService;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return  bool
     */
    public function authorize()
    {
        return $this->user()->role_id == RoleRepository::ADMIN_ROLE;
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
            throw new NotFoundHttpException('Option does not exists');
        }
    }
}

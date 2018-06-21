<?php

namespace App\Http\Requests\Setting;

use App\Repositories\RoleRepository;
use Illuminate\Foundation\Http\FormRequest;

class CreateSettingRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->role_id == RoleRepository::ADMIN_ROLE;
    }

    public function rules()
    {
        return [
            'key' => 'required',
            'value' => 'required'
        ];
    }
}

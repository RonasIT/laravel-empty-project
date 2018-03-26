<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'password' => 'string|same:confirm',
            'confirm' => 'string',
            'email' => 'string|email|unique_except_of_current_user',
            'name' => 'string',
        ];
    }
}
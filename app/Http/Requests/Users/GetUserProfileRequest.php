<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class GetUserProfileRequest extends FormRequest
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
            'email' => "string|email|unique:users,email,{$this->user()->id}",
            'name' => 'string',
        ];
    }
}
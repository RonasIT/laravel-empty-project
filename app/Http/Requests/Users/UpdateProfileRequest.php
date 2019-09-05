<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\Request;

class UpdateProfileRequest extends Request
{
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
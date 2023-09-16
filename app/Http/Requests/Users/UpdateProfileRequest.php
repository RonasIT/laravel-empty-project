<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\Request;

class UpdateProfileRequest extends Request
{
    public function rules(): array
    {
        return [
            'old_password' => 'required_with:password|string|current_password',
            'password' => 'string|confirmed',
            'password_confirmation' => 'string',
            'email' => 'string|email|unique_except_of_authorized_user',
            'name' => 'string',
        ];
    }
}

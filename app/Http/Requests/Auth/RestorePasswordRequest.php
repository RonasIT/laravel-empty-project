<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;

class RestorePasswordRequest extends Request
{
    public function rules(): array
    {
        return [
            'token' => 'required|string|exists:users,set_password_hash',
            'password' => 'required|string'
        ];
    }
}

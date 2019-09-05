<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;

class CheckRestoreTokenRequest extends Request
{
    public function rules()
    {
        return [
            'token' => 'required|string|exists:users,reset_password_hash'
        ];
    }
}

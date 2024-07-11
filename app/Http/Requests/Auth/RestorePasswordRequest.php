<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;

class RestorePasswordRequest extends Request
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'token' => 'required|string|exists:password_reset_tokens,token',
            'password' => 'required|string',
        ];
    }
}

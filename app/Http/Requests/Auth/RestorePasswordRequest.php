<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;

class RestorePasswordRequest extends Request
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string',
        ];
    }
}

<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;

class LoginRequest extends Request
{
    public function rules(): array
    {
        return [
            'email' => 'required',
            'password' => 'required',
            'remember' => 'boolean',
        ];
    }
}

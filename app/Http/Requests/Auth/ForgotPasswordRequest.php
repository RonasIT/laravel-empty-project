<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;

class ForgotPasswordRequest extends Request
{
    public function rules()
    {
        return [
            'email' => 'string|email|exists:users,email'
        ];
    }
}

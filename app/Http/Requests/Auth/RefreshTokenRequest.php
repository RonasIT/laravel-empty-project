<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;

class RefreshTokenRequest extends Request
{
    public function rules(): array
    {
        return [
            'remember' => 'boolean',
        ];
    }
}

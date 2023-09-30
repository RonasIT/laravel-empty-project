<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\Request;

class GetUserProfileRequest extends Request
{
    public function rules(): array
    {
        return [
            'with' => 'array',
            'with.*' => 'string|required',
        ];
    }
}

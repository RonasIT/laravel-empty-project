<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\Request;

class DeleteProfileRequest extends Request
{
    public function authorize(): bool
    {
        return $this->user()->isUser();
    }
}

<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\Request;

class CreateUserRequest extends Request
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'role_id' => 'integer|exists:roles,id',
            'password' => 'string|required',
            'name' => 'string|required',
            'email' => 'required|email|unique:users,email',
        ];
    }
}

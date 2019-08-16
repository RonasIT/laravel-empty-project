<?php

namespace App\Http\Requests\Users;

use App\Models\Role;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->role_id == Role::ADMIN;
    }

    public function rules()
    {
        return [
            'role_id' => 'integer|exists:roles,id',
            'password' => 'string|required',
            'name' => 'string|required',
            'email' => 'required|email|unique:users,email',
        ];
    }

    public function validateResolved()
    {
        parent::validateResolved();

        if ($this->has('role_id') && $this->user()->role_id !== Role::ADMIN) {
            throw new AccessDeniedHttpException('User does not exist');
        }
    }
}
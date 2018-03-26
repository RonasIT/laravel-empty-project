<?php

namespace App\Http\Requests\Users;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\RoleRepository;

class CreateUserRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->role_id == RoleRepository::ADMIN_ROLE;
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

        if ($this->has('role_id') && $this->user()->role_id !== RoleRepository::ADMIN_ROLE) {
            throw new AccessDeniedHttpException('User does not exist');
        }
    }
}
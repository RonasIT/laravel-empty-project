<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\Request;
use App\Models\Role;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CreateUserRequest extends Request
{
    public function authorize(): bool
    {
        return $this->user()->role_id === Role::ADMIN;
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

    public function validateResolved(): void
    {
        parent::validateResolved();

        if ($this->has('role_id') && $this->user()->role_id !== Role::ADMIN) {
            throw new AccessDeniedHttpException('User does not exist');
        }
    }
}

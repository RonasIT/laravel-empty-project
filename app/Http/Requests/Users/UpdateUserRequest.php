<?php

namespace App\Http\Requests\Users;

use App\Models\Role;
use App\Services\UserService;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->role_id == Role::ADMIN ||
            $this->user()->id == $this->route('id');
    }

    public function rules()
    {
        return [
            'email' => "string|email|unique:users,email,{$this->route('id')}",
            'name' => 'string',
        ];
    }

    public function validateResolved()
    {
        parent::validateResolved();

        $service = app(UserService::class);

        if (!$service->exists($this->route('id'))) {
            throw new NotFoundHttpException(__('validation.exceptions.not_found', ['entity' => 'User']));
        }

        if ($this->has('role_id') && $this->user()->role_id !== Role::ADMIN) {
            throw new AccessDeniedHttpException(__('validation.exceptions.not_found', ['entity' => 'User']));
        }
    }
}

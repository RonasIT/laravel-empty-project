<?php

namespace App\Http\Requests\Users;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use App\Services\UserService;
use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\RoleRepository;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->role_id == RoleRepository::ADMIN_ROLE ||
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

        if (!$service->exists(['id' => $this->route('id')])) {
            throw new NotFoundHttpException(__('validation.exceptions.not_found', ['entity' => 'User']));
        }

        if ($this->has('role_id') && $this->user()->role_id !== RoleRepository::ADMIN_ROLE) {
            throw new AccessDeniedHttpException(__('validation.exceptions.not_found', ['entity' => 'User']));
        }
    }
}
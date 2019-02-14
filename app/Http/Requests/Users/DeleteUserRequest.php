<?php

namespace App\Http\Requests\Users;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\UserService;
use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\RoleRepository;

class DeleteUserRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->role_id == RoleRepository::ADMIN_ROLE;
    }

    public function rules()
    {
        return [];
    }

    public function validateResolved()
    {
        parent::validateResolved();

        $service = app(UserService::class);

        if (!$service->exists(['id' => $this->route('id')])) {
            throw new NotFoundHttpException(__('validation.exceptions.not_found', ['entity' => 'User']));
        }
    }
}
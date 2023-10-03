<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\Request;
use App\Services\UserService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteUserRequest extends Request
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin() && ($this->user()->id !== (int) $this->route('id'));
    }

    public function validateResolved(): void
    {
        parent::validateResolved();

        $service = app(UserService::class);

        if (!$service->exists($this->route('id'))) {
            throw new NotFoundHttpException(__('validation.exceptions.not_found', ['entity' => 'User']));
        }
    }
}

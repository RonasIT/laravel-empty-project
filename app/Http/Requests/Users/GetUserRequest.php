<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\Request;
use App\Services\UserService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetUserRequest extends Request
{
    public function rules(): array
    {
        $availableRelations = implode(',', $this->getAvailableRelations());

        return [
            'with' => 'array',
            'with.*' => "required|string|in:{$availableRelations}",
        ];
    }

    public function validateResolved(): void
    {
        parent::validateResolved();

        $service = app(UserService::class);

        if (!$service->exists($this->route('id'))) {
            throw new NotFoundHttpException(__('validation.exceptions.not_found', ['entity' => 'User']));
        }
    }

    protected function getAvailableRelations(): array
    {
        return [
            'role',
        ];
    }
}

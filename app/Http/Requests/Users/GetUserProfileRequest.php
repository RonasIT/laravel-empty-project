<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\Request;

class GetUserProfileRequest extends Request
{
    public function rules(): array
    {
        $availableRelations = implode(',', $this->getAvailableRelations());

        return [
            'with' => 'array',
            'with.*' => "required|string|in:{$availableRelations}",
        ];
    }

    protected function getAvailableRelations(): array
    {
        return [
            'role',
        ];
    }
}

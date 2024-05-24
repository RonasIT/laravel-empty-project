<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\Request;

class SearchUserRequest extends Request
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $availableRelations = implode(',', $this->getAvailableRelations());

        return [
            'role_id' => 'integer|nullable',
            'page' => 'integer|nullable',
            'per_page' => 'integer|nullable',
            'all' => 'integer|nullable',
            'query' => 'string|nullable',
            'order_by' => 'string|nullable',
            'desc' => 'boolean|nullable',
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

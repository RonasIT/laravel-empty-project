<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\Request;
use App\Models\Role;

class SearchUserRequest extends Request
{
    public function authorize(): bool
    {
        return $this->user()->role_id === Role::ADMIN;
    }

    public function rules(): array
    {
        return [
            'role_id' => 'integer|nullable',
            'page' => 'integer|nullable',
            'per_page' => 'integer|nullable',
            'all' => 'integer|nullable',
            'query' => 'string|nullable',
            'order_by' => 'string|nullable',
            'desc' => 'boolean|nullable',
        ];
    }
}

<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\Request;
use App\Models\User;

class SearchUserRequest extends Request
{
    public function rules(): array
    {
        return [
            'page' => 'integer|nullable',
            'per_page' => 'integer|nullable',
            'all' => 'integer|nullable',
            'query' => 'string|nullable',
            'order_by' => 'string|in:' . $this->getOrderableFields(User::class),
            'desc' => 'boolean|nullable',
        ];
    }
}

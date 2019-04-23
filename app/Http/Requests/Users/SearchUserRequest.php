<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\RoleRepository;

class SearchUserRequest extends FormRequest
{
    public function authorize()
    {
        //TODO revert irt
//        return $this->user()->role_id == RoleRepository::ADMIN_ROLE;
        return true;
    }

    public function rules()
    {
        return [
            'role_id' => 'integer|nullable',
            'page' => 'integer|nullable',
            'per_page' => 'integer|nullable',
            'all' => 'integer|nullable',
            'query' => 'string|nullable',
        ];
    }
}

<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\RoleRepository;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return  bool
     */
    public function authorize()
    {
        return $this->user()->role_id == RoleRepository::ADMIN_ROLE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return  array
     */
    public function rules()
    {
        return [
            'role_id' => 'integer|exists:roles,id',
            'password' => 'string|required_unless:role_id,' . RoleRepository::USER_ROLE,
            'name' => 'string|required',
            'email' => 'required|email|unique:users,email',
        ];
    }

}
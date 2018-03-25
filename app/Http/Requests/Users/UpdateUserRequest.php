<?php

namespace App\Http\Requests\Users;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\UserService;
use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\RoleRepository;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return  bool
     */
    public function authorize()
    {
        return $this->user()->role_id == RoleRepository::ADMIN_ROLE ||
            $this->user()->id == $this->route('id');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return  array
     */
    public function rules()
    {
        return [
            'password' => 'string|same:confirm',
            'confirm' => 'string',
            'email' => "string|email|unique:users,email,{$this->route('id')}",
            'name' => 'string',
        ];
    }

    public function validateResolved()
    {
        parent::validateResolved();

        $service = app(UserService::class);

        if (!$service->exists(['id' => $this->route('id')])) {
            throw new NotFoundHttpException('User does not exist');
        }
    }
}
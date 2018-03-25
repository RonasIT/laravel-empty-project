<?php

namespace App\Http\Requests\Users;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\UserService;
use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\RoleRepository;

class DeleteUserRequest extends FormRequest
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
        return [];
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
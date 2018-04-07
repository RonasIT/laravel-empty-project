<?php

namespace App\Http\Requests\Media;

use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\RoleRepository;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class CreateMediaRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->role_id == RoleRepository::ADMIN_ROLE;
    }

    public function rules()
    {
        return [
            //
        ];
    }

    public function validateResolved()
    {
        parent::validateResolved();

        if (empty($this->route('fileName'))) {
            throw new UnprocessableEntityHttpException();
        }
    }
}
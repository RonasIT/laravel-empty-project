<?php

namespace App\Http\Requests\Media;

use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\RoleRepository;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class CreateMediaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $types = implode(',', config('defaults.permitted_media_types'));

        return [
            'file' => "file|required|max:5120|mimes:{$types}"
        ];
    }
}
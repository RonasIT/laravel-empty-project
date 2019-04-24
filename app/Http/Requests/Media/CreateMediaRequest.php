<?php

namespace App\Http\Requests\Media;

use Illuminate\Foundation\Http\FormRequest;

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

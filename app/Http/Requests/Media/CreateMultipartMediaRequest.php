<?php

namespace App\Http\Requests\Media;

use Illuminate\Foundation\Http\FormRequest;

class CreateMultipartMediaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'file' => 'file|required|max:5120'
        ];
    }
}
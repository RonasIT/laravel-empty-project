<?php

namespace App\Http\Requests\tests;

use Illuminate\Foundation\Http\FormRequest;

class CreatetestRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'votes' => 'numeric|required',
            'name' => 'string|required',
            '' => 'date|required',
        ];
    }

}
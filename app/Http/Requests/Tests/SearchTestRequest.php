<?php

namespace App\Http\Requests\Tests;

use Illuminate\Foundation\Http\FormRequest;

class SearchTestRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'page' => 'integer|nullable',
            'per_page' => 'integer|nullable',
            'all' => 'integer|nullable',
            'votes' => 'numeric|nullable',
            'query' => 'string|nullable',
        ];
    }

}
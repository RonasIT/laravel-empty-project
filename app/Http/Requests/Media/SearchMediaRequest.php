<?php

namespace App\Http\Requests\Media;

use Illuminate\Foundation\Http\FormRequest;

class SearchMediaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'page' => 'integer',
            'per_page' => 'integer',
            'all' => 'integer',
            'query' => 'string',
            'order_by' => 'string',
            'desc' => 'boolean',
            'name' => 'string'
        ];
    }
}
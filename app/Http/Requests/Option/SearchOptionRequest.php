<?php

namespace App\Http\Requests\Option;

use Illuminate\Foundation\Http\FormRequest;

class SearchOptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return  bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return  array
     */
    public function rules()
    {
        return [
            'page' => 'integer',
            'per_page' => 'integer',
            'all' => 'integer',
            'query' => 'string|nullable',
            'order_by' => 'string|in:key',
            'desc' => 'boolean'
        ];
    }
}

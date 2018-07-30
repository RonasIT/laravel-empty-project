<?php

namespace App\Http\Requests\Setting;

use App\Repositories\RoleRepository;
use Illuminate\Foundation\Http\FormRequest;

class SearchSettingRequest extends FormRequest
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
            'query' => 'string|nullable',
            'order_by' => 'string|in:name',
            'desc' => 'boolean'
        ];
    }
}

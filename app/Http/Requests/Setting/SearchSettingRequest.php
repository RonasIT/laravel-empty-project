<?php

namespace App\Http\Requests\Setting;

use App\Http\Requests\Request;

class SearchSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'page' => 'integer',
            'per_page' => 'integer',
            'all' => 'integer',
            'query' => 'string|nullable',
            'order_by' => 'string|in:name',
            'desc' => 'boolean',
        ];
    }
}

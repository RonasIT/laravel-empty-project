<?php

namespace App\Http\Requests\Media;

use App\Http\Requests\Request;

class SearchMediaRequest extends Request
{
    public function rules(): array
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

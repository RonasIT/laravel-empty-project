<?php

namespace App\Modules\Media\Http\Requests;

use RonasIT\Support\BaseRequest;

class SearchMediaRequest extends BaseRequest
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

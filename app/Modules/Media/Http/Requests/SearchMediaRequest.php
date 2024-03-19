<?php

namespace App\Modules\Media\Http\Requests;

use App\Modules\Media\Contracts\Requests\SearchMediaRequestContract;
use RonasIT\Support\BaseRequest;

class SearchMediaRequest extends BaseRequest implements SearchMediaRequestContract
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
            'name' => 'string',
        ];
    }
}

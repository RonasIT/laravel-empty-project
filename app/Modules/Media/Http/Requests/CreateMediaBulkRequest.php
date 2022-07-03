<?php

namespace App\Modules\Media\Http\Requests;

use RonasIT\Support\BaseRequest;

class CreateMediaBulkRequest extends BaseRequest
{
    public function rules(): array
    {
        $types = implode(',', config('media.permitted_types'));

        return [
            'media' => 'required|array',
            'media.*' => 'array',
            'media.*.file' => "file|required|max:5120|mimes:$types",
            'media.*.meta' => 'string',
            'media.*.is_public' => 'boolean',
        ];
    }
}

<?php

namespace App\Modules\Media\Http\Requests;

use RonasIT\Support\BaseRequest;

class CreateMediaRequest extends BaseRequest
{
    public function rules(): array
    {
        $types = implode(',', config('media.permitted_types'));

        return [
            'file' => "file|required|max:5120|mimes:{$types}",
            'meta' => 'string',
            'is_public' => 'boolean',
        ];
    }
}

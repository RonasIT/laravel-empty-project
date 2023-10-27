<?php

namespace App\Modules\Media\Http\Requests;

use App\Modules\Media\Contracts\Requests\CreateMediaRequestContract;
use RonasIT\Support\BaseRequest;

class CreateMediaRequest extends BaseRequest implements CreateMediaRequestContract
{
    public function rules(): array
    {
        $types = implode(',', config('media.permitted_types'));

        return [
            'file' => "file|required|max:5120|mimes:{$types}",
            'meta' => 'array',
            'is_public' => 'boolean'
        ];
    }
}

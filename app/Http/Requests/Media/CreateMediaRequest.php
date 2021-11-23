<?php

namespace App\Http\Requests\Media;

use App\Http\Requests\Request;

class CreateMediaRequest extends Request
{
    public function rules(): array
    {
        $types = implode(',', config('defaults.permitted_media_types'));

        return [
            'file' => "file|required|max:5120|mimes:{$types}",
            'is_public' => 'boolean',
        ];
    }
}

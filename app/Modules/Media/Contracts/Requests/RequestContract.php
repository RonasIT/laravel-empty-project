<?php

namespace App\Modules\Media\Contracts\Requests;

interface RequestContract
{
    /**
     * @param array|string $keys
     * @param mixed $default
     *
     * @return array;
     */
    public function onlyValidated($keys = null, $default = null): array;

    public function rules(): array;

    public function authorize(): bool;
}

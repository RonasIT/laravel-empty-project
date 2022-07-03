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
    function onlyValidated($keys = null, $default = null);

    function rules(): array;

    function authorize();

    function validateResolved();

    function input();
}

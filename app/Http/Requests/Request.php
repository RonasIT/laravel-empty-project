<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use RonasIT\Support\AutoDoc\Traits\AutoDocRequestTrait;

class Request extends FormRequest
{
    use AutoDocRequestTrait;
}

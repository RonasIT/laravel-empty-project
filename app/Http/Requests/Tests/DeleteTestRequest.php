<?php

namespace App\Http\Requests\Tests;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\TestService;
use Illuminate\Foundation\Http\FormRequest;

class DeleteTestRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    public function validateResolved()
    {
        parent::validateResolved();

        $service = app(TestService::class);

        if (!$service->exists(['id' => $this->route('id')])) {
            throw new NotFoundHttpException('Test does not exist');
        }
    }
}
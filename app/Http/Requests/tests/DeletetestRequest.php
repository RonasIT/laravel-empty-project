<?php

namespace App\Http\Requests\tests;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\testService;
use Illuminate\Foundation\Http\FormRequest;

class DeletetestRequest extends FormRequest
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

        $service = app(testService::class);

        if (!$service->exists(['id' => $this->route('id')])) {
            throw new NotFoundHttpException('test does not exist');
        }
    }
}
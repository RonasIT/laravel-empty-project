<?php

namespace App\Http\Requests\tests;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\testService;
use Illuminate\Foundation\Http\FormRequest;

class UpdatetestRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'votes' => 'numeric|nullable',
            'name' => 'string|nullable',
            '' => 'date|nullable',
        ];
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
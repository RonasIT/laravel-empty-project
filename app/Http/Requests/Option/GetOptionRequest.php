<?php

namespace App\Http\Requests\Option;

use App\Services\OptionService;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetOptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return  bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return  array
     */
    public function rules()
    {
        return [];
    }

    public function validateResolved()
    {
        parent::validateResolved();

        $service = app(OptionService::class);

        if (!$service->exists(['key' => $this->route('key')])) {
            throw new NotFoundHttpException('Option does not exists');
        }
    }
}

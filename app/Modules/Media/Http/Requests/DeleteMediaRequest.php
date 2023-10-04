<?php

namespace App\Modules\Media\Http\Requests;

use App\Http\Requests\Request;
use App\Modules\Media\Contracts\Requests\DeleteMediaRequestContract;
use App\Modules\Media\Contracts\Services\MediaServiceContract;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteMediaRequest extends Request implements DeleteMediaRequestContract
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function validateResolved(): void
    {
        parent::validateResolved();

        $service = app(MediaServiceContract::class);

        if (!$service->exists($this->route('id'))) {
            throw new NotFoundHttpException(__('validation.exceptions.not_found', ['entity' => 'Media']));
        }
    }
}

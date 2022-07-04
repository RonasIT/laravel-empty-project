<?php

namespace App\Modules\Media\Http\Requests;

use App\Models\Role;
use App\Modules\Media\Contracts\Requests\DeleteMediaRequestContract;
use App\Modules\Media\Services\MediaService;
use RonasIT\Support\BaseRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use function __;
use function app;

class DeleteMediaRequest extends BaseRequest implements DeleteMediaRequestContract
{
    public function authorize(): bool
    {
        return $this->user()->role_id == Role::ADMIN;
    }

    public function validateResolved(): void
    {
        parent::validateResolved();

        $service = app(MediaService::class);

        if (!$service->exists($this->route('id'))) {
            throw new NotFoundHttpException(__('validation.exceptions.not_found', ['entity' => 'Media']));
        }
    }
}

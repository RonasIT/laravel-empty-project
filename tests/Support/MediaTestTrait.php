<?php

namespace App\Tests\Support;

use App\Modules\Media\Services\MediaService;
use RonasIT\Support\Traits\MockClassTrait;

trait MediaTestTrait
{
    use MockClassTrait;

    public function mockGenerateFilename($fileName = 'file.png'): void
    {
        $this->mockClass(MediaService::class, [
            ['method' => 'generateName', 'result' => $fileName]
        ]);
    }
}

<?php

namespace App\Tests\Support;

use App\Modules\Media\Services\MediaService;
use RonasIT\Support\Traits\MockTrait;

trait MediaTestTrait
{
    use MockTrait;

    public function mockGenerateFilename($callsCount = 1): void
    {
        $this->mockClass(
            class: MediaService::class,
            callChain: array_fill(0, $callsCount, $this->functionCall('generateName', [], 'file.png'))
        );
    }
}

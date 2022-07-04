<?php

namespace App\Modules\Media\Contracts\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface MediaServiceContract
{
    public function search(array $filters): LengthAwarePaginator;

    public function create(string $content, string $fileName, array $data): Model;

    public function bulkCreate(array $data): array;

    /**
     * @param $where array|integer|string
     * @return int
     */
    public function delete($where): int;
}
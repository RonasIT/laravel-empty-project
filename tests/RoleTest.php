<?php

namespace App\Tests;

use App\Services\RoleService;
use PHPUnit\Framework\Attributes\DataProvider;

class RoleTest extends TestCase
{
    public static function getSearchFilters(): array
    {
        return [
            [
                'filter' => ['all' => 1],
                'fixture' => 'search_by_all.json',
            ],
            [
                'filter' => [
                    'page' => 2,
                    'per_page' => 1,
                ],
                'fixture' => 'search_by_page_per.json',
            ],
            [
                'filter' => ['query' => 'us'],
                'fixture' => 'get_roles_by_name.json',
            ],
            [
                'filter' => [
                    'desc' => true,
                    'order_by' => 'name',
                ],
                'fixture' => 'get_roles_check_order.json',
            ],
        ];
    }

    #[DataProvider('getSearchFilters')]
    public function testSearch(array $filter, string $fixture)
    {
        $results = app(RoleService::class)->search($filter);

        $this->assertEqualsFixture($fixture, $results->jsonSerialize());
    }
}

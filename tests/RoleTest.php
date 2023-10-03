<?php

namespace App\Tests;

use App\Services\RoleService;

class RoleTest extends TestCase
{
    public function getSearchFilters()
    {
        return [
            [
                'filter' => ['all' => 1],
                'result' => 'search_by_all.json'
            ],
            [
                'filter' => [
                    'page' => 2,
                    'per_page' => 1,
                ],
                'result' => 'search_by_page_per.json'
            ],
            [
                'filter' => ['query' => 'us'],
                'result' => 'get_roles_by_name.json'
            ],
            [
                'filter' => [
                    'desc' => true,
                    'order_by' => 'name'
                ],
                'result' => 'get_roles_check_order.json'
            ]
        ];
    }

    /**
     * @dataProvider  getSearchFilters
     *
     * @param array $filter
     * @param string $fixture
     */
    public function testSearch($filter, $fixture)
    {
        $results = app(RoleService::class)->search($filter);

        $this->assertEqualsFixture($fixture, $results->jsonSerialize());
    }
}

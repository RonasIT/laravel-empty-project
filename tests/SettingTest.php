<?php

namespace App\Tests;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class SettingTest extends TestCase
{
    protected $admin;
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->admin = User::find(1);
        $this->user = User::find(2);
    }

    public function testCreate()
    {
        $setting = $this->getJsonFixture('new_setting.json');

        $response = $this->actingAs($this->admin)->json('post', '/settings', $setting);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testCreateCheckResponse()
    {
        $setting = $this->getJsonFixture('new_setting.json');

        $response = $this->actingAs($this->admin)->json('post', '/settings', $setting);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson($setting);
    }

    public function testCreateNoAuth()
    {
        $setting = $this->getJsonFixture('new_setting.json');

        $response = $this->json('post', '/settings', $setting);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testCreateNoPermission()
    {
        $setting = $this->getJsonFixture('new_setting.json');

        $response = $this->actingAs($this->user)->json('post', '/settings', $setting);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testCreateUnprocessableEntity()
    {
        $setting = $this->getJsonFixture('unprocessable_setting.json');

        $response = $this->actingAs($this->admin)->json('post', '/settings', $setting);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testUpdate()
    {
        $setting = $this->getJsonFixture('update_setting.json');

        $response = $this->actingAs($this->admin)->json('put', "/settings/{$setting['name']}", $setting['value']);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function testUpdateNotExists()
    {
        $setting = $this->getJsonFixture('update_setting.json');

        $response = $this->actingAs($this->admin)->json('put', "/settings/not-exists", $setting['value']);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testUpdateNoAuth()
    {
        $setting = $this->getJsonFixture('update_setting.json');

        $response = $this->json('put', '/settings/1', $setting);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testUpdateNoPermission()
    {
        $setting = $this->getJsonFixture('update_setting.json');

        $response = $this->actingAs($this->user)->json('put', '/settings/1', $setting);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testDelete()
    {
        $response = $this->actingAs($this->admin)->json('delete', '/settings/states');

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function testDeleteNotExists()
    {
        $response = $this->actingAs($this->admin)->json('delete', '/settings/0');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testDeleteNoAuth()
    {
        $response = $this->json('delete', '/settings/1');

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testDeleteNoPermission()
    {
        $response = $this->actingAs($this->user)->json('delete', '/settings/1');

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testGetAsAdmin()
    {
        $response = $this->actingAs($this->admin)->json('get', '/settings/states');

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testGetAsUser()
    {
        $response = $this->actingAs($this->user)->json('get', '/settings/states');

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testGetNoPermission()
    {
        $response = $this->actingAs($this->user)->json('get', '/settings/mailgun');

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testGetCheckResponse()
    {
        $response = $this->actingAs($this->admin)->json('get', '/settings/states');

        $this->assertEqualsFixture('get_setting.json', $response->json());
    }

    public function testGetNotExists()
    {
        $response = $this->actingAs($this->admin)->json('get', '/settings/0');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function getSearchFilters()
    {
        return [
            [
                'filter' => ['query' => 'states'],
                'result' => 'get_setting_by_key.json'
            ],
            [
                'filter' => [
                    'order_by' => 'key',
                    'desc' => false
                ],
                'result' => 'get_settings_check_order.json'
            ],
            [
                'filters' => [
                    'per_page' => 2
                ],
                'fixture' => 'search_per_page.json'
            ],
            [
                'filters' => [
                    'all' => 1
                ],
                'fixture' => 'search_all.json'
            ],
            [
                'filters' => [
                    'per_page' => 1,
                    'query' => 'states',
                ],
                'fixture' => 'search_complex.json'
            ]
        ];
    }

    /**
     * @dataProvider  getSearchFilters
     *
     * @param  array $filter
     * @param  string $fixture
     */
    public function testSearch($filter, $fixture)
    {
        $response = $this->actingAs($this->admin)->json('get', '/settings', $filter);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertEqualsFixture($fixture, $response->json());
    }

    public function getUserSearchFilters()
    {
        return [
            [
                'filter' => [],
                'result' => 'get_public_settings.json'
            ]
        ];
    }

    /**
     * @dataProvider  getUserSearchFilters
     *
     * @param  array $filter
     * @param  string $fixture
     */
    public function testSearchByUser($filter, $fixture)
    {
        $response = $this->actingAs($this->user)->json('get', '/settings', $filter);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertEqualsFixture($fixture, $response->json());
    }
}
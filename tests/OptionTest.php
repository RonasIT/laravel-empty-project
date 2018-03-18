<?php

namespace App\Tests;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class OptionTest extends TestCase
{
    protected $admin;
    protected $user;

    public function setUp() {
        parent::setUp();

        $this->admin = User::find(1);
        $this->user = User::find(2);
    }

    public function testCreate() {
        $option = $this->getJsonFixture('new_option.json');

        $response = $this->actingAs($this->admin)->json('post', '/options', $option);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testCreateCheckResponse() {
        $option = $this->getJsonFixture('new_option.json');

        $response = $this->actingAs($this->admin)->json('post', '/options', $option);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson($option);
    }

    public function testCreateNoAuth() {
        $option = $this->getJsonFixture('new_option.json');

        $response = $this->json('post', '/options', $option);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testCreateNoPermission() {
        $option = $this->getJsonFixture('new_option.json');

        $response = $this->actingAs($this->user)->json('post', '/options', $option);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testCreateUnprocessableEntity() {
        $option = $this->getJsonFixture('unprocessable_option.json');

        $response = $this->actingAs($this->admin)->json('post', '/options', $option);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testUpdate() {
        $option = $this->getJsonFixture('update_option.json');

        $response = $this->actingAs($this->admin)->json('put', "/options/{$option['key']}", $option['value']);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function testUpdateNotExists() {
        $option = $this->getJsonFixture('update_option.json');

        $response = $this->actingAs($this->admin)->json('put', "/options/not-exists", $option['value']);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testUpdateNoAuth() {
        $option = $this->getJsonFixture('update_option.json');

        $response = $this->json('put', '/options/1', $option);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testUpdateNoPermission() {
        $option = $this->getJsonFixture('update_option.json');

        $response = $this->actingAs($this->user)->json('put', '/options/1', $option);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testDelete() {
        $response = $this->actingAs($this->admin)->json('delete', '/options/visa_types');

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function testDeleteNotExists() {
        $response = $this->actingAs($this->admin)->json('delete', '/options/0');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testDeleteNoAuth() {
        $response = $this->json('delete', '/options/1');

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testDeleteNoPermission() {
        $response = $this->actingAs($this->user)->json('delete', '/options/1');

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testGet() {
        $response = $this->actingAs($this->admin)->json('get', '/options/states');

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testGetCheckResponse() {
        $response = $this->actingAs($this->admin)->json('get', '/options/states');

        $this->assertEqualsFixture('get_option.json', $response->json());
    }

    public function testGetNotExists() {
        $response = $this->actingAs($this->admin)->json('get', '/options/0');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function getSearchFilters() {
        return [
            [
                'filter' => ['query' => 'states'],
                'result' => 'get_option_by_key.json'
            ],
            [
                'filter' => [
                    'order_by' => 'key',
                    'desc' => false
                ],
                'result' => 'get_options_check_order.json'
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
    public function testSearch($filter, $fixture) {
        $response = $this->actingAs($this->admin)->json('get', '/options', $filter);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertEqualsFixture($fixture, $response->json());
    }
}
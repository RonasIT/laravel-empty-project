<?php

namespace App\Tests;

use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class TestTest extends TestCase
{
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = User::find(1);
    }

    public function testCreate()
    {
        $data = $this->getJsonFixture('create_test.json');

        $response = $this->actingAs($this->user)->json('post', '/tests', $data);

        $response->assertStatus(Response::HTTP_OK);

        $expect = array_except($data, ['id', 'updated_at', 'created_at']);
        $actual = array_except($response->json(), ['id', 'updated_at', 'created_at']);

        $this->assertEquals($expect, $actual);
    }

    public function testCreateNoAuth()
    {
        $data = $this->getJsonFixture('create_test.json');

        $response = $this->json('post', '/tests', $data);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testUpdate()
    {
        $data = $this->getJsonFixture('update_test.json');

        $response = $this->actingAs($this->user)->json('put', '/tests/1', $data);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function testUpdateNotExists()
    {
        $data = $this->getJsonFixture('update_test.json');

        $response = $this->actingAs($this->user)->json('put', '/tests/0', $data);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testUpdateNoAuth()
    {
        $data = $this->getJsonFixture('update_test.json');

        $response = $this->json('put', '/tests/1', $data);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testDelete()
    {
        $response = $this->actingAs($this->user)->json('delete', '/tests/1');

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function testDeleteNotExists()
    {
        $response = $this->actingAs($this->user)->json('delete', '/tests/0');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testDeleteNoAuth()
    {
        $response = $this->json('delete', '/tests/1');

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testGet()
    {
        $response = $this->actingAs($this->user)->json('get', '/tests/1');

        $response->assertStatus(Response::HTTP_OK);

        // TODO: Need to remove after first successful start
        $this->exportJson($response->json(), 'get_test.json');

        $this->assertEqualsFixture('get_test.json', $response->json());
    }

    public function testGetNotExists()
    {
        $response = $this->actingAs($this->user)->json('get', '/tests/0');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function getSearchFilters()
    {
        return [
            [
                'filter' => ['all' => 1],
                'result' => 'search_all.json'
            ],
            [
                'filter' => ['page' => 1],
                'result' => 'search_by_page.json'
            ],
            [
                'filter' => ['per_page' => 1],
                'result' => 'search_per_page.json'
            ],
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
        $response = $this->json('get', '/tests', $filter);

        // TODO: Need to remove after first successful start
        $this->exportJson($response->json(), $fixture);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertEqualsFixture($fixture, $response->json());
    }
}
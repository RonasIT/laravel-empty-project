<?php

namespace App\Tests;

use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class UserTest extends TestCase
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
        $data = $this->getJsonFixture('create_user.json');

        $response = $this->actingAs($this->admin)->json('post', '/users', $data);

        $response->assertStatus(Response::HTTP_OK);

        $expect = array_except($data, ['id', 'password', 'updated_at', 'created_at']);
        $actual = array_except($response->json(), ['id', 'updated_at', 'created_at']);

        $this->assertEquals($expect, $actual);
    }

    public function testCreateNoAuth()
    {
        $data = $this->getJsonFixture('create_user.json');

        $response = $this->json('post', '/users', $data);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testCreateNoPermission()
    {
        $data = $this->getJsonFixture('user.json');

        $response = $this->actingAs($this->user)->json('post', '/users', $data);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testCreateUserExists()
    {
        $response = $this->actingAs($this->admin)->json('post', '/users', $this->user->toArray());

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testUpdate()
    {
        $data = $this->getJsonFixture('update_user.json');

        $response = $this->actingAs($this->admin)->json('put', '/users/2', $data);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function testUpdateNoPermission()
    {
        $data = $this->getJsonFixture('user.json');

        $response = $this->actingAs($this->user)->json('put', '/users/1', $data);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testUpdateWithEmailOfAnotherUser()
    {
        $response = $this->actingAs($this->admin)->json('put', '/users/2', [
            'email' => 'admin@example.com'
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testUpdateNotExists()
    {
        $data = $this->getJsonFixture('update_user.json');

        $response = $this->actingAs($this->admin)->json('put', '/users/0', $data);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testUpdateNoAuth()
    {
        $data = $this->getJsonFixture('update_user.json');

        $response = $this->json('put', '/users/1', $data);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testUpdateProfile()
    {
        $data = $this->getJsonFixture('update_user.json');

        $data['email'] = 'test@example.com';

        $response = $this->actingAs($this->admin)->json('put', '/profile', $data);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function testUpdateProfileNoAuth()
    {
        $data = $this->getJsonFixture('update_user.json');

        $response = $this->json('put', '/profile', $data);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testDelete()
    {
        $response = $this->actingAs($this->admin)->json('delete', '/users/1');

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function testDeleteNotExists()
    {
        $response = $this->actingAs($this->admin)->json('delete', '/users/0');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testDeleteNoAuth()
    {
        $response = $this->json('delete', '/users/1');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testGetProfile()
    {
        $response = $this->actingAs($this->admin)->json('get', '/profile');

        $response->assertStatus(Response::HTTP_OK);

        $this->assertEqualsFixture('get_user.json', $response->json());
    }

    public function testGet()
    {
        $response = $this->actingAs($this->admin)->json('get', '/users/1');

        $response->assertStatus(Response::HTTP_OK);

        $this->assertEqualsFixture('get_user.json', $response->json());
    }

    public function testGetNotExists()
    {
        $response = $this->actingAs($this->admin)->json('get', '/users/0');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function getSearchFilters()
    {
        return [
            [
                'filter' => ['all' => 1],
                'result' => 'search_by_all_user.json'
            ],
            [
                'filter' => ['page' => 1],
                'result' => 'search_by_page_user.json'
            ],
            [
                'filter' => ['per_page' => 1],
                'result' => 'search_by_per_page_user.json'
            ],
            [
                'filter' => ['query' => 'Another User'],
                'result' => 'get_users_by_name.json'
            ],
            [
                'filter' => ['query' => 'admin@example.com'],
                'result' => 'get_users_by_email.json'
            ],
            [
                'filter' => ['query' => 'Admin'],
                'result' => 'get_users_by_query.json'
            ],
            [
                'filter' => [
                    'query' => 'Admin',
                    'order_by' => 'created_at',
                    'desc' => false
                ],
                'result' => 'get_users_complex.json'
            ],
            [
                'filter' => [
                    'desc' => false,
                    'order_by' => 'name'
                ],
                'result' => 'get_users_check_order.json'
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
        $response = $this->actingAs($this->admin)->json('get', '/users', $filter);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertEqualsFixture($fixture, $response->json());
    }
}
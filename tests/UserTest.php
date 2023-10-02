<?php

namespace App\Tests;

use App\Models\User;
use Illuminate\Support\Arr;

class UserTest extends TestCase
{
    protected $admin;
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = User::find(1);
        $this->user = User::find(2);
    }

    public function testCreate()
    {
        $data = $this->getJsonFixture('create_user.json');

        $response = $this->actingAs($this->admin)->json('post', '/users', $data);

        $response->assertOk();

        $this->assertEqualsFixture('user_created.json', $response->json());

        $this->assertDatabaseHas('users', $this->getJsonFixture('user_created_database.json'));
    }

    public function testCreateNoAuth()
    {
        $data = $this->getJsonFixture('create_user.json');

        $response = $this->json('post', '/users', $data);

        $response->assertUnauthorized();

        $this->assertDatabaseMissing('users', Arr::except($data, ['password']));
    }

    public function testCreateNoPermission()
    {
        $data = $this->getJsonFixture('create_user.json');

        $response = $this->actingAs($this->user)->json('post', '/users', $data);

        $response->assertForbidden();

        $this->assertDatabaseMissing('users', Arr::except($data, ['password']));
    }

    public function testCreateUserExists()
    {
        $response = $this->actingAs($this->admin)->json('post', '/users', $this->user->toArray());

        $response->assertUnprocessable();
    }

    public function testUpdate()
    {
        $data = $this->getJsonFixture('update_user.json');

        $response = $this->actingAs($this->admin)->json('put', '/users/2', $data);

        $response->assertNoContent();

        $this->assertDatabaseHas('users', $data);
    }

    public function testUpdateByUser()
    {
        $data = $this->getJsonFixture('update_user.json');

        $response = $this->actingAs($this->user)->json('put', '/users/2', $data);

        $response->assertForbidden();
    }

    public function testUpdateWithEmailOfAnotherUser()
    {
        $response = $this->actingAs($this->admin)->json('put', '/users/2', [
            'email' => 'admin@example.com'
        ]);

        $response->assertUnprocessable();

        $this->assertDatabaseMissing('users', [
            'id' => 2,
            'email' => 'admin@example.com'
        ]);
    }

    public function testUpdateNotExists()
    {
        $data = $this->getJsonFixture('update_user.json');

        $response = $this->actingAs($this->admin)->json('put', '/users/0', $data);

        $response->assertNotFound();
    }

    public function testUpdateNoAuth()
    {
        $data = $this->getJsonFixture('update_user.json');

        $response = $this->json('put', '/users/1', $data);

        $response->assertUnauthorized();

        $this->assertDatabaseMissing('users', $data);
    }

    public function testUpdateProfile()
    {
        $data = $this->getJsonFixture('update_user.json');

        $response = $this->actingAs($this->admin)->json('put', '/profile', $data);

        $response->assertNoContent();

        $this->assertDatabaseHas('users', $data);
    }

    public function testUpdateProfileWithPassword()
    {
        $data = $this->getJsonFixture('update_profile_with_password.json');

        $response = $this->actingAs($this->user)->json('put', '/profile', $data);

        $response->assertNoContent();
    }

    public function testUpdateProfileWithPasswordEmptyOldPassword()
    {
        $data = $this->getJsonFixture('update_profile_with_password_without_old.json');

        $response = $this->actingAs($this->user)->json('put', '/profile', $data);

        $response->assertUnprocessable();
    }

    public function testUpdateProfileWithPasswordWrongOldPassword()
    {
        $data = $this->getJsonFixture('update_profile_with_password_with_wrong_old.json');

        $response = $this->actingAs($this->user)->json('put', '/profile', $data);

        $response->assertUnprocessable();
    }

    public function testUpdateProfileNoAuth()
    {
        $data = $this->getJsonFixture('update_user.json');

        $response = $this->json('put', '/profile', $data);

        $response->assertUnauthorized();

        $this->assertDatabaseMissing('users', $data);
    }

    public function testDeleteProfile()
    {
        $response = $this->actingAs($this->user)->json('delete', '/profile');

        $response->assertNoContent();

        $response->assertCookieExpired('token');

        $this->assertDatabaseMissing('users', [
            'id' => 2
        ]);

        $this->assertDatabaseMissing('media', [
            'owner_id' => 2
        ]);
    }

    public function testDeleteProfileWithoutBlacklist()
    {
        config(['jwt.blacklist_enabled' => false]);

        $response = $this->actingAs($this->user)->json('delete', '/profile');

        $response->assertUnauthorized();
    }

    public function testDeleteProfileNoAuth()
    {
        $response = $this->json('delete', '/profile');

        $response->assertUnauthorized();
    }

    public function testDeleteProfileAsAdmin()
    {
        $response = $this->actingAs($this->admin)->json('delete', '/profile');

        $response->assertForbidden();
    }

    public function testDelete()
    {
        $response = $this->actingAs($this->admin)->json('delete', '/users/2');

        $response->assertNoContent();

        $this->assertDatabaseMissing('users', [
            'id' => 2
        ]);
    }

    public function testDeleteOwnUser()
    {
        $response = $this->actingAs($this->admin)->json('delete', '/users/1');

        $response->assertForbidden();
    }

    public function testDeleteNotExists()
    {
        $response = $this->actingAs($this->admin)->json('delete', '/users/0');

        $response->assertNotFound();
    }

    public function testDeleteNoAuth()
    {
        $response = $this->json('delete', '/users/1');

        $response->assertUnauthorized();

        $this->assertDatabaseHas('users', [
            'id' => 1
        ]);
    }

    public function testGetProfile()
    {
        $response = $this->actingAs($this->admin)->json('get', '/profile', [
            'with' => ['role', 'media.owner']
        ]);

        $response->assertOk();

        $this->assertEqualsFixture('get_user.json', $response->json());
    }

    public function testGet()
    {
        $response = $this->actingAs($this->admin)->json('get', '/users/1', [
            'with' => ['role', 'media.owner']
        ]);

        $response->assertOk();

        $this->assertEqualsFixture('get_user.json', $response->json());
    }

    public function testGetNotExists()
    {
        $response = $this->actingAs($this->admin)->json('get', '/users/0');

        $response->assertNotFound();
    }

    public function getSearchFilters()
    {
        return [
            [
                'filter' => ['all' => 1],
                'result' => 'search_by_all_user.json'
            ],
            [
                'filter' => [
                    'page' => 1,
                    'per_page' => 2,
                ],
                'result' => 'search_by_page_per_page_user.json'
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
                    'with' => ['role'],
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
     * @param array $filter
     * @param string $fixture
     */
    public function testSearch($filter, $fixture)
    {
        $response = $this->actingAs($this->admin)->json('get', '/users', $filter);

        $response->assertOk();

        $this->assertEqualsFixture($fixture, $response->json());
    }
}

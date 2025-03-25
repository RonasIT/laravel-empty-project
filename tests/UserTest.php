<?php

namespace App\Tests;

use App\Models\User;
use App\Tests\Support\AuthTestTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use RonasIT\Support\Testing\ModelTestState;

class UserTest extends TestCase
{
    use AuthTestTrait;

    protected static User $admin;
    protected static User $user;

    protected static ModelTestState $userState;

    public function setUp(): void
    {
        parent::setUp();

        self::$admin ??= User::find(1);
        self::$user ??= User::find(2);

        self::$userState ??= new ModelTestState(User::class);
    }

    public function testCreate()
    {
        $this->mockBcryptHasher('123123');

        $data = $this->getJsonFixture('create_user');

        $response = $this->actingAs(self::$admin)->json('post', '/users', $data);

        $response->assertCreated();

        $this->assertEqualsFixture('user_created', $response->json());

        self::$userState->assertChangesEqualsFixture('user_created_users_state');
    }

    public function testCreateNoAuth()
    {
        $data = $this->getJsonFixture('create_user');

        $response = $this->json('post', '/users', $data);

        $response->assertUnauthorized();

        self::$userState->assertNotChanged();
    }

    public function testCreateNoPermission()
    {
        $data = $this->getJsonFixture('create_user');

        $response = $this->actingAs(self::$user)->json('post', '/users', $data);

        $response->assertForbidden();

        self::$userState->assertNotChanged();
    }

    public function testCreateUserExists()
    {
        $response = $this->actingAs(self::$admin)->json('post', '/users', self::$user->toArray());

        $response->assertUnprocessable();
    }

    public function testUpdate()
    {
        $data = $this->getJsonFixture('update_user');

        $response = $this->actingAs(self::$admin)->json('put', '/users/2', $data);

        $response->assertNoContent();

        self::$userState->assertChangesEqualsFixture('user_updated_users_state');
    }

    public function testUpdateByUser()
    {
        $data = $this->getJsonFixture('update_user');

        $response = $this->actingAs(self::$user)->json('put', '/users/2', $data);

        $response->assertForbidden();
    }

    public function testUpdateWithEmailOfAnotherUser()
    {
        $response = $this->actingAs(self::$admin)->json('put', '/users/2', [
            'email' => 'admin@example.com',
        ]);

        $response->assertUnprocessable();

        self::$userState->assertNotChanged();
    }

    public function testUpdateNotExists()
    {
        $data = $this->getJsonFixture('update_user');

        $response = $this->actingAs(self::$admin)->json('put', '/users/0', $data);

        $response->assertNotFound();
    }

    public function testUpdateNoAuth()
    {
        $data = $this->getJsonFixture('update_user');

        $response = $this->json('put', '/users/1', $data);

        $response->assertUnauthorized();

        self::$userState->assertNotChanged();
    }

    public function testUpdateProfile()
    {
        $data = $this->getJsonFixture('update_user');

        $response = $this->actingAs(self::$admin)->json('put', '/profile', $data);

        $response->assertNoContent();

        self::$userState->assertChangesEqualsFixture('profile_updated_users_state');
    }

    public function testUpdateProfileWithPassword()
    {
        $data = $this->getJsonFixture('update_profile_with_password');

        $response = $this->actingAs(self::$user)->json('put', '/profile', $data);

        $response->assertNoContent();
    }

    public function testUpdateProfileWithPasswordEmptyOldPassword()
    {
        $data = $this->getJsonFixture('update_profile_with_password_without_old');

        $response = $this->actingAs(self::$user)->json('put', '/profile', $data);

        $response->assertUnprocessable();
    }

    public function testUpdateProfileWithPasswordWrongOldPassword()
    {
        $data = $this->getJsonFixture('update_profile_with_password_with_wrong_old');

        $response = $this->actingAs(self::$user)->json('put', '/profile', $data);

        $response->assertUnprocessable();
    }

    public function testUpdateProfileNoAuth()
    {
        $data = $this->getJsonFixture('update_user');

        $response = $this->json('put', '/profile', $data);

        $response->assertUnauthorized();

        self::$userState->assertNotChanged();
    }

    public function testDeleteProfile()
    {
        $response = $this->actingAs(self::$user)->json('delete', '/profile');

        $response->assertNoContent();

        $response->assertCookieExpired('token');

        $this->assertDatabaseMissing('users', [
            'id' => 2,
        ]);
    }

    public function testDeleteProfileWithoutBlacklist()
    {
        config(['jwt.blacklist_enabled' => false]);

        $response = $this->actingAs(self::$user)->json('delete', '/profile');

        $response->assertUnauthorized();
    }

    public function testDeleteProfileNoAuth()
    {
        $response = $this->json('delete', '/profile');

        $response->assertUnauthorized();
    }

    public function testDeleteProfileAsAdmin()
    {
        $response = $this->actingAs(self::$admin)->json('delete', '/profile');

        $response->assertForbidden();
    }

    public function testDelete()
    {
        $response = $this->actingAs(self::$admin)->json('delete', '/users/2');

        $response->assertNoContent();

        self::$userState->assertChangesEqualsFixture('user_deleted_users_state');
    }

    public function testDeleteOwnUser()
    {
        $response = $this->actingAs(self::$admin)->json('delete', '/users/1');

        $response->assertForbidden();
    }

    public function testDeleteNotExists()
    {
        $response = $this->actingAs(self::$admin)->json('delete', '/users/0');

        $response->assertNotFound();
    }

    public function testDeleteNoAuth()
    {
        $response = $this->json('delete', '/users/1');

        $response->assertUnauthorized();

        self::$userState->assertNotChanged();
    }

    public function testGetProfile()
    {
        $response = $this->actingAs(self::$admin)->json('get', '/profile', [
            'with' => ['role'],
        ]);

        $response->assertOk();

        $this->assertEqualsFixture('get_user', $response->json());
    }

    public function testGet()
    {
        $response = $this->actingAs(self::$admin)->json('get', '/users/1', [
            'with' => ['role'],
        ]);

        $response->assertOk();

        $this->assertEqualsFixture('get_user', $response->json());
    }

    public function testGetNotExists()
    {
        $response = $this->actingAs(self::$admin)->json('get', '/users/0');

        $response->assertNotFound();
    }

    public static function getSearchFilters(): array
    {
        return [
            [
                'filter' => ['all' => 1],
                'fixture' => 'search_by_all_user',
            ],
            [
                'filter' => [
                    'page' => 1,
                    'per_page' => 2,
                ],
                'fixture' => 'search_by_page_per_page_user',
            ],
            [
                'filter' => ['query' => 'Another User'],
                'fixture' => 'get_users_by_name',
            ],
            [
                'filter' => ['query' => 'admin@example.com'],
                'fixture' => 'get_users_by_email',
            ],
            [
                'filter' => ['query' => 'Admin'],
                'fixture' => 'get_users_by_query',
            ],
            [
                'filter' => [
                    'query' => 'Admin',
                    'with' => ['role'],
                    'order_by' => 'created_at',
                    'desc' => false,
                ],
                'fixture' => 'get_users_complex',
            ],
            [
                'filter' => [
                    'desc' => false,
                    'order_by' => 'name',
                ],
                'fixture' => 'get_users_check_order',
            ],
        ];
    }

    #[DataProvider('getSearchFilters')]
    public function testSearch(array $filter, string $fixture)
    {
        $response = $this->actingAs(self::$admin)->json('get', '/users', $filter);

        $response->assertOk();

        $this->assertEqualsFixture($fixture, $response->json());
    }
}

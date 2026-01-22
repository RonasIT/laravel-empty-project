<?php

namespace App\Tests;

use App\Models\User;
use App\Tests\Support\AuthTestTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use RonasIT\Support\Testing\ModelTestState;

class UserTest extends TestCase
{
    use AuthTestTrait;

    protected static User $user;

    protected static ModelTestState $userState;

    public function setUp(): void
    {
        parent::setUp();

        self::$user ??= User::find(1);

        self::$userState ??= new ModelTestState(User::class);
    }

    public function testUpdateProfile()
    {
        $data = $this->getJsonFixture('update_user');

        $response = $this->actingAs(self::$user)->json('put', '/profile', $data);

        $response->assertNoContent();

        self::$userState->assertChangesEqualsFixture('profile_updated_users_state');
    }

    public function testUpdateProfileWithPassword()
    {
        $data = $this->getJsonFixture('update_profile_with_password');

        $response = $this->actingAs(User::find(2))->json('put', '/profile', $data);

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

        $this->assertDatabaseMissing('users', ['id' => 1]);
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

    public function testGetProfile()
    {
        $response = $this->actingAs(self::$user)->json('get', '/profile');

        $response->assertOk();

        $this->assertEqualsFixture('get_user', $response->json());
    }

    public function testGet()
    {
        $response = $this->actingAs(self::$user)->json('get', '/users/1', [
            'with' => ['role'],
        ]);

        $response->assertOk();

        $this->assertEqualsFixture('get_user', $response->json());
    }

    public function testGetIdParamAsString()
    {
        $response = $this->actingAs(self::$user)->json('get', '/users/test');

        $response->assertNotFound();
    }

    public function testPutIdParamAsString()
    {
        $response = $this->actingAs(self::$user)->json('put', '/users/test');

        $response->assertNotFound();
    }

    public function testDeleteIdParamAsString()
    {
        $response = $this->actingAs(self::$user)->json('delete', '/users/test');

        $response->assertNotFound();
    }

    public function testGetNotExists()
    {
        $response = $this->actingAs(self::$user)->json('get', '/users/0');

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
        $response = $this->actingAs(self::$user)->json('get', '/users', $filter);

        $response->assertOk();

        $this->assertEqualsFixture($fixture, $response->json());
    }
}

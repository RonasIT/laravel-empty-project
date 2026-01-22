<?php

namespace App\Tests;

use App\Models\User;
use App\Services\SettingService;
use PHPUnit\Framework\Attributes\DataProvider;

class SettingTest extends TestCase
{
    protected static User $admin;
    protected static User $user;

    public function setUp(): void
    {
        parent::setUp();

        self::$admin ??= User::find(1);
        self::$user ??= User::find(2);
    }

    public function testGetAsAdmin()
    {
        $response = $this->actingAs(self::$admin)->json('get', '/settings/states');

        $response->assertOk();
    }

    public function testGetAsUser()
    {
        $response = $this->actingAs(self::$user)->json('get', '/settings/states');

        $response->assertOk();
    }

    public function testGetNoPermission()
    {
        $response = $this->actingAs(self::$user)->json('get', '/settings/mailgun');

        $response->assertForbidden();
    }

    public function testGetCheckResponse()
    {
        $response = $this->actingAs(self::$admin)->json('get', '/settings/states');

        $this->assertEqualsFixture('get_setting', $response->json());
    }

    public function testGetNotExists()
    {
        $response = $this->actingAs(self::$admin)->json('get', '/settings/0');

        $response->assertForbidden();
    }

    public static function getSearchFilters(): array
    {
        return [
            [
                'filter' => ['query' => 'states'],
                'fixture' => 'get_setting_by_key',
            ],
            [
                'filter' => [
                    'order_by' => 'name',
                    'desc' => false,
                ],
                'fixture' => 'get_settings_check_order',
            ],
            [
                'filter' => [
                    'per_page' => 2,
                ],
                'fixture' => 'search_per_page',
            ],
            [
                'filter' => [
                    'all' => 1,
                ],
                'fixture' => 'search_all',
            ],
            [
                'filter' => [
                    'per_page' => 1,
                    'query' => 'states',
                ],
                'fixture' => 'search_complex',
            ],
        ];
    }

    #[DataProvider('getSearchFilters')]
    public function testSearch($filter, $fixture)
    {
        $response = $this->actingAs(self::$admin)->json('get', '/settings', $filter);

        $response->assertOk();

        $this->assertEqualsFixture($fixture, $response->json());
    }

    public static function getUserSearchFilters(): array
    {
        return [
            [
                'filter' => [],
                'fixture' => 'get_public_settings',
            ],
        ];
    }

    #[DataProvider('getUserSearchFilters')]
    public function testSearchByUser(array $filter, string $fixture)
    {
        $response = $this->actingAs(self::$user)->json('get', '/settings', $filter);

        $response->assertOk();

        $this->assertEqualsFixture($fixture, $response->json());
    }

    public function testSetNotExistsSetting()
    {
        $setting = [
            'name' => 'test.value',
            'value' => 123,
        ];

        $result = app(SettingService::class)->set($setting['name'], $setting['value']);

        $this->assertEqualsFixture('setting_set_not_exists', $result->jsonSerialize());

        $this->assertDatabaseHas('settings', [
            'name' => $setting['name'],
            'value' => json_encode($setting['value']),
        ]);
    }

    public function testSetExistsSetting()
    {
        $setting = [
            'name' => 'attribute',
            'value' => 'new value',
        ];

        $result = app(SettingService::class)->set($setting['name'], $setting['value']);

        $this->assertEqualsFixture('setting_set_exists', $result->jsonSerialize());

        $this->assertDatabaseHas('settings', [
            'name' => $setting['name'],
            'value' => json_encode($setting['value']),
        ]);
    }

    public function testGetExistsSetting()
    {
        $result = app(SettingService::class)->get('attribute');

        $this->assertEqualsFixture('setting_get_exists', $result);
    }

    public function testGetExistsJsonSetting()
    {
        $result = app(SettingService::class)->get('settings.timezone');

        $this->assertEqualsFixture('setting_get_exists_json', $result);
    }

    public function testGetNotExistsSetting()
    {
        $result = app(SettingService::class)->get('not_exists_attribute');

        $this->assertEqualsFixture('setting_get_not_exists', $result);
    }
}

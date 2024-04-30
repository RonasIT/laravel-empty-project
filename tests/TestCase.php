<?php

namespace App\Tests;

use App\Enums\VersionEnum;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\TestResponse;
use RonasIT\Support\AutoDoc\Tests\AutoDocTestCaseTrait;
use RonasIT\Support\Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use AutoDocTestCaseTrait;

    protected static bool $isJwtGuard;

    protected string $token;

    public function setUp(): void
    {
        parent::setUp();

        $defaultGuard = config('auth.defaults.guard');

        self::$isJwtGuard = config("auth.guards.{$defaultGuard}.driver") === 'jwt';
    }

    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication(): Application
    {
        /** @var Application $app */
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->loadEnvironmentFrom('.env.testing');
        $app->make(Kernel::class)->bootstrap();

        $this->truncateExceptTables = ['migrations', 'password_resets', 'roles'];
        $this->prepareSequencesExceptTables = ['migrations', 'password_resets', 'settings', 'roles'];

        return $app;
    }

    public function actingAs(Authenticatable $user, $guard = null): self
    {
        if (!self::$isJwtGuard) {
            return parent::actingAs($user, $guard);
        }

        $this->token = Auth::fromUser($user);

        return $this;
    }

    public function call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null): TestResponse
    {
        if (self::$isJwtGuard && !empty($this->token)) {
            $server['HTTP_AUTHORIZATION'] = "Bearer {$this->token}";
        }

        return parent::call($method, $uri, $parameters, $cookies, $files, $server, $content);
    }

    public function json($method, $uri, array $data = [], array $headers = [], $options = 0, ?VersionEnum $apiVersion = null): TestResponse
    {
        $apiVersion = (empty($apiVersion)) ? last(VersionEnum::values()) : $apiVersion->value;

        return parent::json($method, "/v{$apiVersion}{$uri}", $data, $headers);
    }
}

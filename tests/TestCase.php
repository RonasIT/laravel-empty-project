<?php

namespace App\Tests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\TestResponse;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Auth\Authenticatable;
use RonasIT\Support\Tests\TestCase as BaseTestCase;
use RonasIT\Support\AutoDoc\Tests\AutoDocTestCaseTrait;

abstract class TestCase extends BaseTestCase
{
    use AutoDocTestCaseTrait;

    protected string $token;
    protected static bool $isJwtGuard;

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
}

<?php

namespace App\Tests;

use Illuminate\Testing\TestResponse;
use Illuminate\Contracts\Auth\Authenticatable;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;
use RonasIT\Support\Tests\TestCase as BaseTestCase;
use RonasIT\Support\AutoDoc\Tests\AutoDocTestCaseTrait;

abstract class TestCase extends BaseTestCase
{
    use AutoDocTestCaseTrait;

    protected $jwt;
    protected $auth;

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

    public function actingAs(Authenticatable $user, $driver = null): self
    {
        $this->jwt = $this->auth->fromUser($user);

        return $this;
    }

    public function call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null): TestResponse
    {
        if (!empty($this->jwt)) {
            $server['HTTP_AUTHORIZATION'] = "Bearer {$this->jwt}";
        }

        return parent::call($method, $uri, $parameters, $cookies, $files, $server, $content);
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->auth = app(JWTAuth::class);
    }
}

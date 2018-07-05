<?php

namespace App\Tests;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;
use RonasIT\Support\AutoDoc\Tests\AutoDocTestCase;
use RonasIT\Support\Traits\FixturesTrait;
use Tymon\JWTAuth\JWTAuth;

abstract class TestCase extends AutoDocTestCase
{
    use FixturesTrait;

    protected $jwt;
    protected $auth;

    public function setUp()
    {
        parent::setUp();

        $this->artisan("cache:clear");
        $this->artisan('migrate');

        $this->loadTestDump();
        $this->auth = app(JWTAuth::class);
    }

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->loadEnvironmentFrom('.env.testing');
        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    public function tearDown()
    {
        $this->beforeApplicationDestroyed(function () {
            DB::disconnect();
        });

        parent::tearDown();
    }

    public function call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        $options = array_filter([
            'X-CSRF-TOKEN' => null,
            'Authorization' => empty($this->jwt) ? null : "Bearer {$this->jwt}",
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ]);

        $server = array_merge(
            $this->transformHeadersToServerVars($options),
            $server
        );

        return parent::call($method, $uri, $parameters, $cookies, $files, $server, $content);
    }

    public function actingAs(Authenticatable $user, $driver = null)
    {
        $this->jwt = $this->auth->fromUser($user);

        return $this;
    }

}

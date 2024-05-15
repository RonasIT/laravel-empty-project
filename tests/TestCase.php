<?php

namespace App\Tests;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\ParallelTesting;
use Illuminate\Testing\Concerns\TestDatabases;
use Illuminate\Testing\TestResponse;
use RonasIT\Support\AutoDoc\Tests\AutoDocTestCaseTrait;
use RonasIT\Support\Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use AutoDocTestCaseTrait;
    use TestDatabases;

    protected static bool $isJwtGuard;

    protected string $token;

    public function setUp(): void
    {
        static::$latestResponse = null;

        Facade::clearResolvedInstances();

        if (! $this->app) {
            $this->refreshApplication();

            ParallelTesting::callSetUpTestCaseCallbacks($this);
        }

        $this->setUpTraits();

        foreach ($this->afterApplicationCreatedCallbacks as $callback) {
            $callback();
        }

        Model::setEventDispatcher($this->app['events']);

        $this->setUpHasRun = true;

        $this->whenNotUsingInMemoryDatabase(function ($database) {
            [$testDatabase, $created] = $this->ensureTestDatabaseExists($database);

            $this->switchToDatabase($testDatabase);
        });

        $this->artisan('cache:clear');

        if ((static::$startedTestSuite !== static::class) || !self::$isWrappedIntoTransaction) {
            $this->artisan('migrate');

            $this->loadTestDump();

            static::$startedTestSuite = static::class;
        }

        if (config('database.default') === 'pgsql') {
            $this->prepareSequences();
        }

        Carbon::setTestNow(Carbon::parse($this->testNow));

        Mail::fake();

        $this->beginDatabaseTransaction();

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

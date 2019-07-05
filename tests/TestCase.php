<?php

namespace App\Tests;

use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Console\Kernel;
use RonasIT\Support\Traits\FixturesTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use RonasIT\Support\AutoDoc\Tests\AutoDocTestCase;

abstract class TestCase extends AutoDocTestCase
{
    use FixturesTrait;

    protected $jwt;
    protected $auth;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('cache:clear');
        $this->artisan('migrate');

        $this->loadTestDump();
        $this->auth = app(JWTAuth::class);
        Mail::fake();
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

    public function tearDown(): void
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

    protected function assertMailEquals($mailableClass, $data)
    {
        $index = 0;

        Mail::assertSent($mailableClass, function ($mail) use ($data, &$index) {
            $sentEmails = array_pluck($mail->to, 'address');
            $currentMail = array_get($data, $index);
            $emails = array_wrap($currentMail['emails']);
            $subject = array_get($currentMail, 'subject');

            if (!empty($subject)) {
                $this->assertEquals($currentMail['subject'], $mail->subject);
            }

            $this->assertEquals(count($mail->to), count($emails));

            $emailList = implode(',', $sentEmails);

            foreach ($emails as $email) {
                $this->assertContains($email, $sentEmails, "Block \"To\" on {$index} step don't contains {$email}. Contains only {$emailList}.");
            }

            $this->assertEquals(
                $this->getFixture($currentMail['fixture']),
                view($mail->view, $mail->getData())->render(),
                "Fixture {$currentMail['fixture']} does not equals rendered mail."
            );

            $index++;

            return true;
        });
    }
}

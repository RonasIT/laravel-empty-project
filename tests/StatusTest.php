<?php

namespace App\Tests;

use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class StatusTest extends TestCase
{
    public function testStatusOk()
    {
        $response = $this->json('get', '/status');

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testStatusServiceUnavailable()
    {
        DB::shouldReceive('disconnect')->andReturnNull();
        DB::shouldReceive('getPdo')->andThrow(Exception::class);

        $response = $this->json('get', '/status');

        $response->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE);
    }
}
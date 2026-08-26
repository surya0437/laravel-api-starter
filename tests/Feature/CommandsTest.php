<?php

use Illuminate\Support\Facades\DB;

test('status command runs successfully', function () {
    $this->artisan('api-starter:status')
        ->assertExitCode(0);
});

test('health command runs successfully', function () {
    DB::shouldReceive('connection->getPdo')->andReturn(true);

    $this->artisan('api-starter:health')
        ->assertExitCode(0);
});

test('generator commands run successfully', function () {
    $this->artisan('make:api-controller', ['name' => 'TestController', '--force' => true])
        ->assertExitCode(0);

    $this->artisan('make:api-resource', ['name' => 'TestResource', '--force' => true])
        ->assertExitCode(0);

    $this->artisan('make:api-request', ['name' => 'TestRequest', '--force' => true])
        ->assertExitCode(0);

    $this->artisan('make:api-service', ['name' => 'TestService', '--force' => true])
        ->assertExitCode(0);
});

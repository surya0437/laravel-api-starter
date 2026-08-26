<?php

use Illuminate\Support\Facades\DB;

test('health check endpoint returns ok', function () {
    DB::shouldReceive('connection->getPdo')->andReturn(true);

    $response = $this->getJson('/api/v1/health');

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => [
                'status' => 'ok',
                'database' => 'ok',
                'cache' => 'ok',
            ],
        ]);
});

<?php

return [

    'enabled' => env('API_STARTER_ENABLED', true),

    'prefix' => env('API_STARTER_PREFIX', 'api'),

    'versioning' => [
        'enabled' => env('API_STARTER_VERSIONING_ENABLED', true),
        'default' => env('API_STARTER_VERSIONING_DEFAULT', 'v1'),
    ],

    'authentication' => [
        'enabled' => env('API_STARTER_AUTH_ENABLED', true),
        'driver' => 'sanctum',
    ],

    'pagination' => [
        'default' => 15,
        'max' => 100,
    ],

    'rate_limit' => [
        'enabled' => env('API_STARTER_RATE_LIMIT_ENABLED', true),
        'requests' => (int) env('API_STARTER_RATE_LIMIT_REQUESTS', 60),
        'minutes' => (int) env('API_STARTER_RATE_LIMIT_MINUTES', 1),
    ],

    'logging' => [
        'requests' => env('API_STARTER_LOG_REQUESTS', false),
        'activity' => env('API_STARTER_LOG_ACTIVITY', false),
    ],

    'health' => [
        'enabled' => env('API_STARTER_HEALTH_ENABLED', true),
        'database' => env('API_STARTER_HEALTH_DATABASE', true),
        'cache' => env('API_STARTER_HEALTH_CACHE', true),
    ],

    'response' => [
        'include_message' => true,
        'include_meta' => true,
        'include_request_id' => true,
    ],

];

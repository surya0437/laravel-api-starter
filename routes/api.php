<?php

use Illuminate\Support\Facades\Route;
use Surya\ApiStarter\Http\Middleware\RequestIdMiddleware;

/*
|--------------------------------------------------------------------------
| API Starter Routes
|--------------------------------------------------------------------------
*/

$prefix = config('api-starter.prefix', 'api');

Route::prefix($prefix)
    ->middleware(['api', RequestIdMiddleware::class])
    ->group(function () {
        require __DIR__.'/v1.php';
    });

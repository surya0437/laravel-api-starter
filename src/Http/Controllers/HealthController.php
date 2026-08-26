<?php

namespace Surya\ApiStarter\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Throwable;

class HealthController extends ApiController
{
    public function index(): JsonResponse
    {
        $databaseStatus = 'disabled';
        $cacheStatus = 'disabled';
        $isHealthy = true;

        if (config('api-starter.health.database', true)) {
            try {
                DB::connection()->getPdo();
                $databaseStatus = 'ok';
            } catch (Throwable $e) {
                $databaseStatus = 'error';
                $isHealthy = false;
            }
        }

        if (config('api-starter.health.cache', true)) {
            try {
                $testKey = 'api_health_check_'.time();
                Cache::put($testKey, 'ok', 5);
                $value = Cache::get($testKey);
                Cache::forget($testKey);

                $cacheStatus = ($value === 'ok') ? 'ok' : 'error';
                if ($cacheStatus !== 'ok') {
                    $isHealthy = false;
                }
            } catch (Throwable $e) {
                $cacheStatus = 'error';
                $isHealthy = false;
            }
        }

        $data = [
            'status' => $isHealthy ? 'ok' : 'error',
            'database' => $databaseStatus,
            'cache' => $cacheStatus,
        ];

        if ($isHealthy) {
            return $this->success(
                data: $data,
                message: __('api-starter::api.health_check')
            );
        }

        return $this->error(
            message: __('api-starter::api.health_failed'),
            code: 503,
            errors: $data
        );
    }
}

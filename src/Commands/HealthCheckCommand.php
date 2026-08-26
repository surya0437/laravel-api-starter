<?php

namespace Surya\ApiStarter\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Throwable;

class HealthCheckCommand extends Command
{
    protected $signature = 'api-starter:health';

    protected $description = 'Run system health checks (Database & Cache)';

    public function handle(): int
    {
        $this->info('🏥 Running API Health Checks...');
        $this->newLine();

        $allOk = true;

        // Database
        try {
            DB::connection()->getPdo();
            $this->line(' [Database] ✅ Connected successfully.');
        } catch (Throwable $e) {
            $allOk = false;
            $this->error(' [Database] ❌ Connection failed: '.$e->getMessage());
        }

        // Cache
        try {
            $key = 'health_check_'.time();
            Cache::put($key, 'test', 5);
            $val = Cache::get($key);
            Cache::forget($key);

            if ($val === 'test') {
                $this->line(' [Cache]    ✅ Cache driver is functional.');
            } else {
                $allOk = false;
                $this->error(' [Cache]    ❌ Cache value mismatch.');
            }
        } catch (Throwable $e) {
            $allOk = false;
            $this->error(' [Cache]    ❌ Cache error: '.$e->getMessage());
        }

        $this->newLine();

        if ($allOk) {
            $this->info('🎉 All health checks passed!');

            return Command::SUCCESS;
        }

        $this->error('⚠️ Some health checks failed.');

        return Command::FAILURE;
    }
}

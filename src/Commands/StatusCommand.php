<?php

namespace Surya\ApiStarter\Commands;

use Illuminate\Console\Command;

class StatusCommand extends Command
{
    protected $signature = 'api-starter:status';

    protected $description = 'Display configuration and component status for Laravel API Starter';

    public function handle(): int
    {
        $this->info('📊 Laravel API Starter Kit Status');
        $this->newLine();

        $rows = [
            ['Package Enabled', config('api-starter.enabled') ? '✅ Enabled' : '❌ Disabled'],
            ['API Route Prefix', config('api-starter.prefix', 'api')],
            ['Versioning', config('api-starter.versioning.enabled') ? '✅ Default: '.config('api-starter.versioning.default') : '❌ Disabled'],
            ['Authentication Driver', config('api-starter.authentication.driver', 'sanctum')],
            ['Rate Limiting', config('api-starter.rate_limit.enabled') ? '✅ '.config('api-starter.rate_limit.requests').' reqs/'.config('api-starter.rate_limit.minutes').'m' : '❌ Disabled'],
            ['Request Logging', config('api-starter.logging.requests') ? '✅ Enabled' : '❌ Disabled'],
            ['Health Check Endpoint', config('api-starter.health.enabled') ? '✅ Enabled' : '❌ Disabled'],
        ];

        $this->table(['Feature', 'Status / Configuration'], $rows);

        return Command::SUCCESS;
    }
}

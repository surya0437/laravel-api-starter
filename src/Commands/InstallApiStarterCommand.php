<?php

namespace Surya\ApiStarter\Commands;

use Illuminate\Console\Command;

class InstallApiStarterCommand extends Command
{
    protected $signature = 'api-starter:install
                            {--force : Overwrite existing files}
                            {--with-example : Include example CRUD controller, resource, and service}';

    protected $description = 'Install and publish the Laravel API Starter Kit';

    public function handle(): int
    {
        $this->info('🚀 Installing Laravel API Starter Kit...');

        // Publish config
        $this->call('vendor:publish', [
            '--tag' => 'api-starter-config',
            '--force' => $this->option('force'),
        ]);

        // Publish translations
        $this->call('vendor:publish', [
            '--tag' => 'api-starter-translations',
            '--force' => $this->option('force'),
        ]);

        if ($this->option('with-example')) {
            $this->publishExampleResources();
        }

        $this->newLine();
        $this->info('✅ Laravel API Starter Kit installed successfully!');

        $this->comment('Next steps:');
        $this->line(' 1. Check installation status: php artisan api-starter:status');
        $this->line(' 2. Run health check: php artisan api-starter:health');

        return Command::SUCCESS;
    }

    protected function publishExampleResources(): void
    {
        $this->info('📦 Publishing example API resources...');

        $this->call('make:api-crud', [
            'name' => 'Product',
        ]);
    }
}

<?php

namespace Surya\ApiStarter\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeApiCrudCommand extends Command
{
    protected $signature = 'make:api-crud {name : The entity model name (e.g., Product)} {--force : Overwrite existing files}';

    protected $description = 'Generate full API CRUD scaffolding (Controller, Resource, Requests, Service)';

    public function handle(): int
    {
        $name = Str::studly(trim($this->argument('name')));
        $force = $this->option('force') ? ['--force' => true] : [];

        $this->info("🚀 Generating API CRUD for {$name}...");

        // Controller
        $this->call('make:api-controller', array_merge(['name' => "{$name}Controller"], $force));

        // Resource
        $this->call('make:api-resource', array_merge(['name' => "{$name}Resource"], $force));

        // Form Requests
        $this->call('make:api-request', array_merge(['name' => "Store{$name}Request"], $force));
        $this->call('make:api-request', array_merge(['name' => "Update{$name}Request"], $force));

        // Service
        $this->call('make:api-service', array_merge(['name' => "{$name}Service"], $force));

        $this->newLine();
        $this->info("✅ API CRUD scaffolding for [{$name}] generated successfully!");

        return Command::SUCCESS;
    }
}

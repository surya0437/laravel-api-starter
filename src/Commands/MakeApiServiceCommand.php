<?php

namespace Surya\ApiStarter\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeApiServiceCommand extends Command
{
    protected $signature = 'make:api-service {name : The name of the Service} {--force : Overwrite if exists}';

    protected $description = 'Create a new API Service class for business logic';

    public function __construct(protected Filesystem $files)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $name = Str::studly(trim($this->argument('name')));
        if (! str_ends_with($name, 'Service')) {
            $name .= 'Service';
        }

        $path = app_path("Services/{$name}.php");

        if ($this->files->exists($path) && ! $this->option('force')) {
            $this->error("Service {$name} already exists!");

            return Command::FAILURE;
        }

        $stub = $this->getStub($name);

        $this->files->ensureDirectoryExists(dirname($path));
        $this->files->put($path, $stub);

        $this->info("API Service [app/Services/{$name}.php] created successfully.");

        return Command::SUCCESS;
    }

    protected function getStub(string $name): string
    {
        return <<<PHP
<?php

namespace App\Services;

class {$name}
{
    // Write reusable domain / business logic here
}
PHP;
    }
}

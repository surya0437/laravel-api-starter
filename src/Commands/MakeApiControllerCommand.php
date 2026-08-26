<?php

namespace Surya\ApiStarter\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeApiControllerCommand extends Command
{
    protected $signature = 'make:api-controller {name : The name of the API Controller} {--force : Overwrite if exists}';

    protected $description = 'Create a new API Controller extending ApiController';

    public function __construct(protected Filesystem $files)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $name = Str::studly(trim($this->argument('name')));
        if (! str_ends_with($name, 'Controller')) {
            $name .= 'Controller';
        }

        $path = app_path("Http/Controllers/Api/{$name}.php");

        if ($this->files->exists($path) && ! $this->option('force')) {
            $this->error("Controller {$name} already exists!");

            return Command::FAILURE;
        }

        $stub = $this->getStub($name);

        $this->files->ensureDirectoryExists(dirname($path));
        $this->files->put($path, $stub);

        $this->info("API Controller [app/Http/Controllers/Api/{$name}.php] created successfully.");

        return Command::SUCCESS;
    }

    protected function getStub(string $name): string
    {
        return <<<PHP
<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Surya\ApiStarter\Http\Controllers\ApiController;

class {$name} extends ApiController
{
    public function index(Request \$request): JsonResponse
    {
        return \$this->success([], 'Data retrieved successfully.');
    }

    public function store(Request \$request): JsonResponse
    {
        return \$this->created([], 'Resource created successfully.');
    }

    public function show(string \$id): JsonResponse
    {
        return \$this->success([], 'Resource retrieved successfully.');
    }

    public function update(Request \$request, string \$id): JsonResponse
    {
        return \$this->updated([], 'Resource updated successfully.');
    }

    public function destroy(string \$id): JsonResponse
    {
        return \$this->deleted('Resource deleted successfully.');
    }
}
PHP;
    }
}

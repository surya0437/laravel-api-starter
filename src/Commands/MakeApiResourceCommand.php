<?php

namespace Surya\ApiStarter\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeApiResourceCommand extends Command
{
    protected $signature = 'make:api-resource {name : The name of the API Resource} {--force : Overwrite if exists}';

    protected $description = 'Create a new API JsonResource class';

    public function __construct(protected Filesystem $files)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $name = Str::studly(trim($this->argument('name')));
        if (! str_ends_with($name, 'Resource')) {
            $name .= 'Resource';
        }

        $path = app_path("Http/Resources/{$name}.php");

        if ($this->files->exists($path) && ! $this->option('force')) {
            $this->error("Resource {$name} already exists!");

            return Command::FAILURE;
        }

        $stub = $this->getStub($name);

        $this->files->ensureDirectoryExists(dirname($path));
        $this->files->put($path, $stub);

        $this->info("API Resource [app/Http/Resources/{$name}.php] created successfully.");

        return Command::SUCCESS;
    }

    protected function getStub(string $name): string
    {
        return <<<PHP
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class {$name} extends JsonResource
{
    public function toArray(Request \$request): array
    {
        return parent::toArray(\$request);
    }
}
PHP;
    }
}

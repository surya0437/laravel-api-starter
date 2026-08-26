<?php

namespace Surya\ApiStarter\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeApiRequestCommand extends Command
{
    protected $signature = 'make:api-request {name : The name of the Form Request} {--force : Overwrite if exists}';

    protected $description = 'Create a new API Form Request extending ApiFormRequest';

    public function __construct(protected Filesystem $files)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $name = Str::studly(trim($this->argument('name')));
        if (! str_ends_with($name, 'Request')) {
            $name .= 'Request';
        }

        $path = app_path("Http/Requests/{$name}.php");

        if ($this->files->exists($path) && ! $this->option('force')) {
            $this->error("Form Request {$name} already exists!");

            return Command::FAILURE;
        }

        $stub = $this->getStub($name);

        $this->files->ensureDirectoryExists(dirname($path));
        $this->files->put($path, $stub);

        $this->info("API Request [app/Http/Requests/{$name}.php] created successfully.");

        return Command::SUCCESS;
    }

    protected function getStub(string $name): string
    {
        return <<<PHP
<?php

namespace App\Http\Requests;

use Surya\ApiStarter\Http\Requests\ApiFormRequest;

class {$name} extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Define rules here
        ];
    }
}
PHP;
    }
}

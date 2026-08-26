<?php

namespace Surya\ApiStarter\Providers;

use Illuminate\Support\ServiceProvider;
use Surya\ApiStarter\Commands\HealthCheckCommand;
use Surya\ApiStarter\Commands\InstallApiStarterCommand;
use Surya\ApiStarter\Commands\MakeApiControllerCommand;
use Surya\ApiStarter\Commands\MakeApiCrudCommand;
use Surya\ApiStarter\Commands\MakeApiRequestCommand;
use Surya\ApiStarter\Commands\MakeApiResourceCommand;
use Surya\ApiStarter\Commands\MakeApiServiceCommand;
use Surya\ApiStarter\Commands\StatusCommand;

class ApiStarterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/api-starter.php',
            'api-starter'
        );
    }

    public function boot(): void
    {
        if (! config('api-starter.enabled', true)) {
            return;
        }

        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'api-starter');
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        $this->registerRoutes();
        $this->registerPublishing();
        $this->registerCommands();
    }

    protected function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/api.php');
    }

    protected function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            // Config
            $this->publishes([
                __DIR__.'/../../config/api-starter.php' => config_path('api-starter.php'),
            ], 'api-starter-config');

            // Migrations
            $this->publishes([
                __DIR__.'/../../database/migrations' => database_path('migrations'),
            ], 'api-starter-migrations');

            // Routes
            $this->publishes([
                __DIR__.'/../../routes' => base_path('routes/api-starter'),
            ], 'api-starter-routes');

            // Translations
            $this->publishes([
                __DIR__.'/../../resources/lang' => lang_path('vendor/api-starter'),
            ], 'api-starter-translations');

            // Everything tag
            $this->publishes([
                __DIR__.'/../../config/api-starter.php' => config_path('api-starter.php'),
                __DIR__.'/../../database/migrations' => database_path('migrations'),
                __DIR__.'/../../resources/lang' => lang_path('vendor/api-starter'),
            ], 'api-starter');
        }
    }

    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallApiStarterCommand::class,
                StatusCommand::class,
                HealthCheckCommand::class,
                MakeApiControllerCommand::class,
                MakeApiResourceCommand::class,
                MakeApiRequestCommand::class,
                MakeApiServiceCommand::class,
                MakeApiCrudCommand::class,
            ]);
        }
    }
}

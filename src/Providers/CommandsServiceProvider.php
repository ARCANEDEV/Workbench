<?php namespace Arcanedev\Workbench\Providers;

use Arcanedev\Support\ServiceProvider as ServiceProvider;
use Arcanedev\Workbench\Commands\DisableCommand;
use Arcanedev\Workbench\Commands\DumpCommand;
use Arcanedev\Workbench\Commands\EnableCommand;
use Arcanedev\Workbench\Commands\GenerateRouteProviderCommand;
use Arcanedev\Workbench\Commands\InstallCommand;
use Arcanedev\Workbench\Commands\ListCommand;
use Arcanedev\Workbench\Commands\MakeCommand;
use Arcanedev\Workbench\Commands\MakeConsoleCommand;
use Arcanedev\Workbench\Commands\MakeControllerCommand;
use Arcanedev\Workbench\Commands\MakeMiddlewareCommand;
use Arcanedev\Workbench\Commands\MakeMigrationCommand;
use Arcanedev\Workbench\Commands\MakeModelCommand;
use Arcanedev\Workbench\Commands\MakeProviderCommand;
use Arcanedev\Workbench\Commands\MakeRequestCommand;
use Arcanedev\Workbench\Commands\MakeSeedCommand;
use Arcanedev\Workbench\Commands\MigrateCommand;
use Arcanedev\Workbench\Commands\MigrateRefreshCommand;
use Arcanedev\Workbench\Commands\MigrateResetCommand;
use Arcanedev\Workbench\Commands\MigrateRollbackCommand;
use Arcanedev\Workbench\Commands\PublishCommand;
use Arcanedev\Workbench\Commands\PublishMigrationsCommand;
use Arcanedev\Workbench\Commands\PublishTranslationCommand;
use Arcanedev\Workbench\Commands\SeedCommand;
use Arcanedev\Workbench\Commands\SetupCommand;
use Arcanedev\Workbench\Commands\UpdateCommand;
use Arcanedev\Workbench\Commands\UseCommand;
use Closure;

/**
 * Class CommandsServiceProvider
 * @package Arcanedev\Workbench\Providers
 */
class CommandsServiceProvider extends ServiceProvider
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Vendor name.
     *
     * @var string
     */
    protected $vendor   = 'arcanedev';

    /**
     * Package name.
     *
     * @var string
     */
    protected $package  = 'workbench';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer    = false;

    /**
     * @var array
     */
    protected $commands = [];

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerDisableCommand();
        $this->registerDumpCommand();
        $this->registerEnableCommand();
        $this->registerMakeProvider();
        $this->registerGenerateRouteProviderCommand();
        $this->registerInstallCommand();
        $this->registerListCommand();
        $this->registerMakeCommand();
        $this->registerMakeConsoleCommand();
        $this->registerMakeControllerCommand();
        $this->registerMakeMiddlewareCommand();
        $this->registerMakeRequestCommand();
        $this->registerMigrateCommand();
        $this->registerMigrateRefreshCommand();
        $this->registerMigrateRollbackCommand();
        $this->registerMigrateResetCommand();
        $this->registerMigrationCommand();
        $this->registerModelCommand();
        $this->registerPublishCommand();
        $this->registerPublishMigrationsCommand();
        $this->registerPublishTranslationsCommand();
        $this->registerSeedCommand();
        $this->registerSeedMakeCommand();
        $this->registerSetupCommand();
        $this->registerUpdateCommand();
        $this->registerUseCommand();

        $this->commands($this->commands);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return $this->commands;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Command Functions
     | ------------------------------------------------------------------------------------------------
     */
    private function registerDisableCommand()
    {
        $this->registerCommand('disable', function () {
            return new DisableCommand;
        });
    }

    private function registerDumpCommand()
    {
        $this->registerCommand('dump', function () {
            return new DumpCommand;
        });
    }

    private function registerEnableCommand()
    {
        $this->registerCommand('enable', function () {
            return new EnableCommand;
        });
    }

    private function registerMakeProvider()
    {
        $this->registerCommand('make-provider', function () {
            return new MakeProviderCommand;
        });
    }

    private function registerGenerateRouteProviderCommand()
    {
        $this->registerCommand('route-provider', function () {
            return new GenerateRouteProviderCommand();
        });
    }

    private function registerInstallCommand()
    {
        $this->registerCommand('install', function () {
            return new InstallCommand;
        });
    }

    private function registerListCommand()
    {
        $this->registerCommand('list', function () {
            return new ListCommand;
        });
    }

    private function registerMakeCommand()
    {
        $this->registerCommand('make', function () {
            return new MakeCommand;
        });
    }

    private function registerMakeConsoleCommand()
    {
        $this->registerCommand('make-command', function () {
            return new MakeConsoleCommand;
        });
    }

    private function registerMakeControllerCommand()
    {
        $this->registerCommand('make-controller', function () {
            return new MakeControllerCommand;
        });
    }

    private function registerMakeMiddlewareCommand()
    {
        $this->registerCommand('make-middleware', function () {
            return new MakeMiddlewareCommand;
        });
    }

    private function registerMakeRequestCommand()
    {
        $this->registerCommand('make-request', function () {
            return new MakeRequestCommand;
        });
    }

    private function registerMigrateCommand()
    {
        $this->registerCommand('migrate', function () {
            return new MigrateCommand;
        });
    }

    private function registerMigrateRefreshCommand()
    {
        $this->registerCommand('migrate-refresh', function () {
            return new MigrateRefreshCommand;
        });
    }

    private function registerMigrateRollbackCommand()
    {
        $this->registerCommand('migrate-rollback', function () {
            return new MigrateRollbackCommand;
        });
    }
    private function registerMigrateResetCommand()
    {
        $this->registerCommand('migrate-reset', function () {
            return new MigrateResetCommand;
        });
    }

    private function registerMigrationCommand()
    {
        $this->registerCommand('make-migration', function () {
            return new MakeMigrationCommand;
        });
    }

    private function registerModelCommand()
    {
        $this->registerCommand('make-model', function () {
            return new MakeModelCommand;
        });
    }

    private function registerPublishCommand()
    {
        $this->registerCommand('publish', function () {
            return new PublishCommand;
        });
    }

    private function registerPublishMigrationsCommand()
    {
        $this->registerCommand('publish-migrations', function () {
            return new PublishMigrationsCommand;
        });
    }

    private function registerPublishTranslationsCommand()
    {
        $this->registerCommand('publish-translations', function () {
            return new PublishTranslationCommand;
        });
    }

    private function registerSeedCommand()
    {
        $this->registerCommand('seed', function () {
            return new SeedCommand;
        });
    }

    private function registerSeedMakeCommand()
    {
        $this->registerCommand('make-seed', function () {
            return new MakeSeedCommand;
        });
    }

    private function registerSetupCommand()
    {
        $this->registerCommand('setup', function () {
            return new SetupCommand;
        });
    }

    private function registerUpdateCommand()
    {
        $this->registerCommand('update', function () {
            return new UpdateCommand;
        });
    }

    private function registerUseCommand()
    {
        $this->registerCommand('use', function () {
            return new UseCommand;
        });
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register command.
     *
     * @param  string   $name
     * @param  Closure  $callback
     */
    protected function registerCommand($name, Closure $callback)
    {
        $command = $this->vendor . '.' . $this->package . '.commands.' . $name;

        $this->app->singleton($name, $callback);

        $this->commands[] = $command;
    }
}
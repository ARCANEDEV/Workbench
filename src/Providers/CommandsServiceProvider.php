<?php namespace Arcanedev\Workbench\Providers;

use Arcanedev\Workbench\Commands\ConsoleCommand;
use Arcanedev\Workbench\Commands\DisableCommand;
use Arcanedev\Workbench\Commands\DumpCommand;
use Arcanedev\Workbench\Commands\EnableCommand;
use Arcanedev\Workbench\Commands\GenerateFilterCommand;
use Arcanedev\Workbench\Commands\GenerateRouteProviderCommand;
use Arcanedev\Workbench\Commands\InstallCommand;
use Arcanedev\Workbench\Commands\ListCommand;
use Arcanedev\Workbench\Commands\MakeCommand;
use Arcanedev\Workbench\Commands\MakeControllerCommand;
use Arcanedev\Workbench\Commands\MakeProviderCommand;
use Arcanedev\Workbench\Commands\MakeRequestCommand;
use Arcanedev\Workbench\Commands\MigrateCommand;
use Arcanedev\Workbench\Commands\MigrateRefreshCommand;
use Arcanedev\Workbench\Commands\MigrateResetCommand;
use Arcanedev\Workbench\Commands\MigrateRollbackCommand;
use Arcanedev\Workbench\Commands\MigrationCommand;
use Arcanedev\Workbench\Commands\ModelCommand;
use Arcanedev\Workbench\Commands\PublishCommand;
use Arcanedev\Workbench\Commands\PublishMigrationCommand;
use Arcanedev\Workbench\Commands\PublishTranslationCommand;
use Arcanedev\Workbench\Commands\SeedCommand;
use Arcanedev\Workbench\Commands\SeedMakeCommand;
use Arcanedev\Workbench\Commands\SetupCommand;
use Arcanedev\Workbench\Commands\UpdateCommand;
use Arcanedev\Workbench\Commands\UseCommand;
use Illuminate\Support\ServiceProvider;

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
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

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
        foreach ($this->provides() as $command) {
            $this->commands($command);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            ConsoleCommand::class,
            DisableCommand::class,
            DumpCommand::class,
            EnableCommand::class,
            GenerateFilterCommand::class,
            GenerateRouteProviderCommand::class,
            InstallCommand::class,
            ListCommand::class,
            MakeCommand::class,
            MakeControllerCommand::class,
            MakeProviderCommand::class,
            MakeRequestCommand::class,
            MigrateCommand::class,
            MigrateRefreshCommand::class,
            MigrateResetCommand::class,
            MigrateRollbackCommand::class,
            MigrationCommand::class,
            ModelCommand::class,
            PublishCommand::class,
            PublishMigrationCommand::class,
            PublishTranslationCommand::class,
            SeedCommand::class,
            SeedMakeCommand::class,
            SetupCommand::class,
            UpdateCommand::class,
            UseCommand::class,
        ];
    }
}
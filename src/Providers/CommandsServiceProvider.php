<?php namespace Arcanedev\Workbench\Providers;

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
            \Arcanedev\Workbench\Commands\MakeCommand::class,
            \Arcanedev\Workbench\Commands\ConsoleCommand::class,
            \Arcanedev\Workbench\Commands\ControllerCommand::class,
            \Arcanedev\Workbench\Commands\DisableCommand::class,
            \Arcanedev\Workbench\Commands\EnableCommand::class,
            \Arcanedev\Workbench\Commands\GenerateFilterCommand::class,
            \Arcanedev\Workbench\Commands\GenerateProviderCommand::class,
            \Arcanedev\Workbench\Commands\GenerateRouteProviderCommand::class,
            \Arcanedev\Workbench\Commands\InstallCommand::class,
            \Arcanedev\Workbench\Commands\ListCommand::class,
            \Arcanedev\Workbench\Commands\MigrateCommand::class,
            \Arcanedev\Workbench\Commands\MigrateRefreshCommand::class,
            \Arcanedev\Workbench\Commands\MigrateResetCommand::class,
            \Arcanedev\Workbench\Commands\MigrateRollbackCommand::class,
            \Arcanedev\Workbench\Commands\MigrationCommand::class,
            \Arcanedev\Workbench\Commands\ModelCommand::class,
            \Arcanedev\Workbench\Commands\PublishCommand::class,
            \Arcanedev\Workbench\Commands\PublishMigrationCommand::class,
            \Arcanedev\Workbench\Commands\PublishTranslationCommand::class,
            \Arcanedev\Workbench\Commands\SeedCommand::class,
            \Arcanedev\Workbench\Commands\SeedMakeCommand::class,
            \Arcanedev\Workbench\Commands\SetupCommand::class,
            \Arcanedev\Workbench\Commands\UpdateCommand::class,
            \Arcanedev\Workbench\Commands\UseCommand::class,
            \Arcanedev\Workbench\Commands\DumpCommand::class,
            \Arcanedev\Workbench\Commands\MakeRequestCommand::class,
        ];
    }
}
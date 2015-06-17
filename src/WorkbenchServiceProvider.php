<?php namespace Arcanedev\Workbench;

use Illuminate\Support\ServiceProvider;

/**
 * Class WorkbenchServiceProvider
 * @package Arcanedev\Workbench
 * @author  ARCANEDEV
 */
class WorkbenchServiceProvider extends ServiceProvider
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
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the command.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            // Commands\CreateCommand::class,
            // Commands\GetCommand::class,
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['workbench'];
    }
}

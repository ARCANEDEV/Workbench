<?php namespace Arcanedev\Workbench;

use Arcanedev\Support\Stub;
use Arcanedev\Workbench\Providers\BootstrapServiceProvider;
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
        $this->app->register(BootstrapServiceProvider::class);
    }

    /**
     * Register the command.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfigs();
        $this->registerServices();
        $this->registerStubs();
        $this->registerProviders();

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

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register all package configs
     */
    private function registerConfigs()
    {
        $configPath = __DIR__ . '/../config/workbench.php';

        $this->mergeConfigFrom($configPath, 'workbench');
        $this->publishes([
            $configPath => config_path('workbench.php')
        ]);
    }

    /**
     * Register all services
     */
    private function registerServices()
    {
        $this->app->bind('workbench', function ($app) {
            /** @var \Illuminate\Config\Repository $config */
            $config = $app['config'];

            return new Workbench($app, $config->get('workbench.paths.modules'));
        });

        $this->app->bind(
            \Arcanedev\Workbench\Contracts\WorkbenchInterface::class,
            \Arcanedev\Workbench\Workbench::class
        );
    }

    /**
     * Setup stub path.
     */
    private function registerStubs()
    {
        $this->app->booted(function ($app) {
            /** @var \Illuminate\Config\Repository $config */
            $config = $app['config'];

            $path = $config->get('workbench.stubs.enabled') === true
                ? $config->get('workbench.stubs.path')
                : __DIR__ . '/../stubs';

            Stub::setBasePath($path);
        });
    }

    /**
     * Register providers.
     */
    protected function registerProviders()
    {
        $this->app->register(Providers\CommandsServiceProvider::class);
    }
}

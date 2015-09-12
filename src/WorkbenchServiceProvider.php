<?php namespace Arcanedev\Workbench;

use Arcanedev\Support\PackageServiceProvider;
use Arcanedev\Support\Stub;
use Arcanedev\Workbench\Providers\BootstrapServiceProvider;

/**
 * Class     WorkbenchServiceProvider
 *
 * @package  Arcanedev\Workbench
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class WorkbenchServiceProvider extends PackageServiceProvider
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
    protected $vendor   = 'workbench';

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

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the base path of the package.
     *
     * @return string
     */
    public function getBasePath()
    {
        return dirname(__DIR__);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->app->register(BootstrapServiceProvider::class);
    }

    /**
     * Register the command.
     */
    public function register()
    {
        $this->registerConfig();
        $this->registerServices();
        $this->registerStubs();
        $this->registerProviders();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'arcanedev.workbench'
        ];
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register all services
     */
    private function registerServices()
    {
        $this->app->singleton('arcanedev.workbench', function ($app) {
            /** @var \Illuminate\Config\Repository $config */
            $config = $app['config'];

            return new Workbench($app, $config->get('workbench.paths.modules'));
        });

        $this->app->bind(Contracts\WorkbenchInterface::class, Workbench::class);
    }

    /**
     * Setup stub path.
     */
    private function registerStubs()
    {
        $this->app->booted(function ($app) {
            /** @var \Illuminate\Config\Repository $config */
            $config = $app['config'];
            $path   = $this->getBasePath() . DS .'stubs';

            if ($config->get('workbench.stubs.enabled', false) === true) {
                $path = (string) $config->get('workbench.stubs.path', $path);
            }

            Stub::setBasePath($path);
        });
    }

    /**
     * Register providers.
     */
    private function registerProviders()
    {
        $this->app->register(Providers\CommandsServiceProvider::class);
    }
}

<?php namespace Arcanedev\Workbench\Providers;

use Arcanedev\Support\ServiceProvider;

/**
 * Class     BootstrapServiceProvider
 *
 * @package  Arcanedev\Workbench\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class BootstrapServiceProvider extends ServiceProvider
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Booting the package.
     *
     * @return void
     */
    public function boot()
    {
        workbench()->boot();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        workbench()->register();
    }
}

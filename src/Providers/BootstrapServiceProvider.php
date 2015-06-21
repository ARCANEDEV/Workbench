<?php namespace Arcanedev\Workbench\Providers;

use Illuminate\Support\ServiceProvider;

class BootstrapServiceProvider extends ServiceProvider
{
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

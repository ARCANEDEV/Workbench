<?php namespace Arcanedev\Workbench\Tests;

use Arcanedev\Workbench\WorkbenchServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        // Your code here
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        //
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            WorkbenchServiceProvider::class,
        ];
    }

    /**
     * Get package aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'Workbench' => \Arcanedev\Workbench\Facades\Workbench::class
        ];
    }
}

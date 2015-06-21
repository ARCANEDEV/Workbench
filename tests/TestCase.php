<?php namespace Arcanedev\Workbench\Tests;

use Arcanedev\Workbench\WorkbenchServiceProvider;

/**
 * Class TestCase
 * @package Arcanedev\Workbench\Tests
 */
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
        $app['config']->set('workbench.stubs.path',      __DIR__ . '/../stubs');
        $app['config']->set(
            'workbench.paths.modules',
            __DIR__ . '/../build/generated/modules'
        );
        $app['config']->set(
            'workbench.paths.assets',
            __DIR__ . '/../build/generated/public/modules'
        );
        $app['config']->set(
            'workbench.paths.migration',
            __DIR__ . '/../build/generated/database/migrations'
        );
        $app['config']->set(
            'workbench.scan.paths', [
                __DIR__ . '/../vendor/*/*'
            ]
        );
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

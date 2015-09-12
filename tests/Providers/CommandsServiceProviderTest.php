<?php namespace Arcanedev\Workbench\Tests\Providers;

use Arcanedev\Workbench\Providers\CommandsServiceProvider;
use Arcanedev\Workbench\Tests\TestCase;

/**
 * Class     CommandsServiceProviderTest
 *
 * @package  Arcanedev\Workbench\Tests\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CommandsServiceProviderTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var CommandsServiceProvider */
    private $provider;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->provider = $this->app->getProvider(CommandsServiceProvider::class);
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->provider);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(CommandsServiceProvider::class, $this->provider);
    }

    /** @test */
    public function it_can_provides()
    {
        $provided = $this->provider->provides();

        $this->assertCount(26, $provided);
        $this->assertEquals([
            'arcanedev.workbench.commands.disable',
            'arcanedev.workbench.commands.dump',
            'arcanedev.workbench.commands.enable',
            'arcanedev.workbench.commands.make-provider',
            'arcanedev.workbench.commands.route-provider',
            'arcanedev.workbench.commands.install',
            'arcanedev.workbench.commands.list',
            'arcanedev.workbench.commands.make',
            'arcanedev.workbench.commands.make-command',
            'arcanedev.workbench.commands.make-controller',
            'arcanedev.workbench.commands.make-middleware',
            'arcanedev.workbench.commands.make-request',
            'arcanedev.workbench.commands.migrate',
            'arcanedev.workbench.commands.migrate-refresh',
            'arcanedev.workbench.commands.migrate-rollback',
            'arcanedev.workbench.commands.migrate-reset',
            'arcanedev.workbench.commands.make-migration',
            'arcanedev.workbench.commands.make-model',
            'arcanedev.workbench.commands.publish',
            'arcanedev.workbench.commands.publish-migrations',
            'arcanedev.workbench.commands.publish-translations',
            'arcanedev.workbench.commands.seed',
            'arcanedev.workbench.commands.make-seed',
            'arcanedev.workbench.commands.setup',
            'arcanedev.workbench.commands.update',
            'arcanedev.workbench.commands.use',
        ], $provided);
    }
}

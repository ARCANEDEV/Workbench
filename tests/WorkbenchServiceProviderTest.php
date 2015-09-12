<?php namespace Arcanedev\Workbench\Tests;
use Arcanedev\Workbench\WorkbenchServiceProvider;

/**
 * Class     WorkbenchServiceProviderTest
 *
 * @package  Arcanedev\Workbench\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class WorkbenchServiceProviderTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var WorkbenchServiceProvider */
    private $provider;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->provider = $this->app->getProvider(WorkbenchServiceProvider::class);
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
        $this->assertInstanceOf(WorkbenchServiceProvider::class, $this->provider);
    }

    /** @test */
    public function it_can_provides()
    {
        $provided = $this->provider->provides();

        $this->assertCount(1, $provided);
        $this->assertEquals([
            'arcanedev.workbench',
        ], $provided);
    }
}

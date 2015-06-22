<?php namespace Arcanedev\Workbench\Tests\Entities;

use Arcanedev\Workbench\Entities\Module;
use Arcanedev\Workbench\Tests\TestCase;
use Mockery as m;

class ModuleTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Module */
    private $module;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->module = $this->createModule();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(Module::class, $this->module);
        $this->assertEquals($this->app, $this->module->getLaravel());
        $this->assertEquals($this->getFixturePath(),  $this->module->getPath());
    }

    /** @test */
    public function it_can_get_module_attributes()
    {
        $name = 'Module';
        $this->assertEquals($name, $this->module->getName());
        $this->assertEquals($name, $this->module->name);
        $this->assertEquals($name, $this->module->get('name'));
        $this->assertEquals($name, (string) $this->module);

        $alias = 'module';
        $this->assertEquals($alias, $this->module->alias);
        $this->assertEquals($alias, $this->module->getAlias());
        $this->assertEquals($alias, $this->module->get('alias'));

        $description = "Module description for tests";
        $this->assertEquals($description, $this->module->description);
        $this->assertEquals($description, $this->module->getDescription());
        $this->assertEquals($description, $this->module->get('description'));

        $keywords = ['module', 'test'];
        $this->assertEquals($keywords, $this->module->keywords);
        $this->assertEquals($keywords, $this->module->getKeywords());
        $this->assertEquals($keywords, $this->module->get('keywords'));

        $this->assertTrue($this->module->active);
        $this->assertTrue($this->module->active());
        $this->assertTrue($this->module->get('active'));
    }

    /** @test */
    public function it_can_enable_and_disable()
    {
        $this->assertTrue($this->module->active());
        $this->assertTrue($this->module->enabled());
        $this->assertFalse($this->module->notActive());
        $this->assertFalse($this->module->disabled());

        $this->module->disable();

        $this->assertFalse($this->module->active());
        $this->assertFalse($this->module->enabled());
        $this->assertTrue($this->module->notActive());
        $this->assertTrue($this->module->disabled());

        $this->module->enable();

        $this->assertTrue($this->module->active());
        $this->assertTrue($this->module->enabled());
        $this->assertFalse($this->module->notActive());
        $this->assertFalse($this->module->disabled());
    }

    /** @test */
    public function it_can_fire_events_on_enabling()
    {
        $events = m::mock('Illuminate\\Contracts\\Events\\Dispatcher');
        $events->shouldReceive('fire')->times(2);

        $this->module->setDispatcher($events);

        $this->module->enable();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a module
     * @param  string $name
     *
     * @return Module
     */
    private function createModule($name = 'Module')
    {
        return new Module($this->app, $name, $this->getFixturePath($name));
    }

    /**
     * Get fixture folder path
     *
     * @param  string $name
     *
     * @return string
     */
    private function getFixturePath($name = 'Module')
    {
        return realpath(__DIR__ . '/fixtures/' . str_slug($name));
    }
}

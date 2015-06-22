<?php namespace Arcanedev\Workbench\Tests\Entities;

use Arcanedev\Workbench\Entities\Module;
use Arcanedev\Workbench\Tests\TestCase;

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

        $this->assertTrue((bool) $this->module->active);
        $this->assertTrue($this->module->active());
        $this->assertTrue((bool) $this->module->get('active'));
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
    private function createModule($name = 'module')
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
    private function getFixturePath($name = 'module')
    {
        return realpath(__DIR__ . '/fixtures/' . $name);
    }
}

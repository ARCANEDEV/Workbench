<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Support\Stub;
use Arcanedev\Workbench\Bases\Command;
use Arcanedev\Workbench\Traits\ModuleCommandTrait;

/**
 * Class ControllerCommand
 * @package Arcanedev\Workbench\Commands
 */
class ControllerCommand extends Command
{
    /* ------------------------------------------------------------------------------------------------
     |  Traits
     | ------------------------------------------------------------------------------------------------
     */
    use ModuleCommandTrait;

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The name of argument being used.
     *
     * @var string
     */
    protected $argumentName = 'controller';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-controller
                            {controller : The name of the controller class.}
                            {module? : The name of module will be used.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new restful controller for the specified module.';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get controller name.
     *
     * @return string
     */
    public function getDestinationFilePath()
    {
        $path           = workbench()->getModulePath($this->getModuleName());
        $controllerPath = workbench()->config('paths.generator.controller');

        return $path . $controllerPath . '/' . $this->getControllerName() . '.php';
    }

    /**
     * @return Stub
     */
    protected function getTemplateContents()
    {
        $module = workbench()->findOrFail($this->getModuleName());

        return (new Stub('/controller.stub', [
            'MODULENAME'        => $module->getStudlyName(),
            'CONTROLLERNAME'    => $this->getControllerName(),
            'CLASS'             => $this->getClass(),
            'NAMESPACE'         => $module->getLowername(),
            'MODULE_NAMESPACE'  => config('workbench.namespace'),
            'CLASS_NAMESPACE'   => $this->getClassNamespace($module),
        ]))->render();
    }

    /**
     * @return array|string
     */
    protected function getControllerName()
    {
        $controller = studly_case($this->argument('controller'));

        if ( ! str_contains(strtolower($controller), 'controller')) {
            $controller = $controller.'Controller';
        }

        return $controller;
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace()
    {
        return 'Controllers';
    }
}

<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Support\Stub;
use Arcanedev\Workbench\Bases\Command;
use Arcanedev\Workbench\Traits\ModuleCommandTrait;

/**
 * Class MakeControllerCommand
 * @package Arcanedev\Workbench\Commands
 */
class MakeControllerCommand extends Command
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
                            {name : The name of the controller class.}
                            {module? : The name of module will be used.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new restful controller for the specified module.';

    /* ------------------------------------------------------------------------------------------------
     |  Getter & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get template contents.
     *
     * @return string
     */
    protected function getTemplateContents()
    {
        $module = $this->getModule();

        return (new Stub('/controller.stub', [
            'MODULENAME'        => $module->getStudlyName(),
            'CONTROLLERNAME'    => $this->getFileName(),
            'CLASS'             => $this->getClass(),
            'NAMESPACE'         => $module->getLowername(),
            'MODULE_NAMESPACE'  => config('workbench.namespace'),
            'CLASS_NAMESPACE'   => $this->getClassNamespace($module),
        ]))->render();
    }

    /**
     * Get the destination file path.
     *
     * @return string
     */
    protected function getDestinationFilePath()
    {
        $path           = workbench()->getModulePath($this->getModuleName());
        $controllerPath = workbench()->config('paths.generator.controller');

        return $path . $controllerPath . '/' . $this->getFileName() . '.php';
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    protected function getDefaultNamespace()
    {
        return 'Controllers';
    }

    /**
     * Get studly file name
     *
     * @return string
     */
    protected function getFileName()
    {
        $name = parent::getFileName();

        if ( ! str_contains(strtolower($name), 'controller')) {
            $name .= 'Controller';
        }

        return $name;
    }
}

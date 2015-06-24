<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Support\Stub;
use Arcanedev\Workbench\Bases\BenchCommand;

/**
 * Class MakeControllerCommand
 * @package Arcanedev\Workbench\Commands
 */
class MakeControllerCommand extends BenchCommand
{
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

        return Stub::create('/controller.stub', [
            'MODULENAME'        => $module->getStudlyName(),
            'CONTROLLERNAME'    => $this->getFileName(),
            'CLASS'             => $this->getClass(),
            'NAMESPACE'         => $module->getLowername(),
            'MODULE_NAMESPACE'  => workbench()->config('namespace'),
            'CLASS_NAMESPACE'   => $this->getClassNamespace($module),
        ])->render();
    }

    /**
     * Get the destination file path.
     *
     * @param  string $name
     *
     * @return string
     */
    protected function getDestinationFilePath($name = '')
    {
        return parent::getDestinationFilePath('controller');
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

<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Support\Stub;
use Arcanedev\Workbench\Bases\Command;

/**
 * Class MakeRequestCommand
 * @package Arcanedev\Workbench\Commands
 */
class MakeRequestCommand extends Command
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The name of argument name.
     *
     * @var string
     */
    protected $argumentName = 'name';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-request
                            {name : The name of the form request class.}
                            {module? : The name of module will be used.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new form request class for the specified module.';

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
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

        return Stub::create('/request.stub', [
            'MODULE'           => $module->getStudlyName(),
            'NAME'             => $this->getFileName(),
            'MODULE_NAMESPACE' => workbench()->config('namespace'),
            'NAMESPACE'        => $this->getClassNamespace($module),
            'CLASS'            => $this->getClass(),
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
        return parent::getDestinationFilePath('request');
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    protected function getDefaultNamespace()
    {
        return 'Requests';
    }
}

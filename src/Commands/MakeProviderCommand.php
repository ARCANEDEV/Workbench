<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Support\Stub;
use Arcanedev\Workbench\Bases\Command;
use Arcanedev\Workbench\Traits\ModuleCommandTrait;

/**
 * Class MakeProviderCommand
 * @package Arcanedev\Workbench\Commands
 */
class MakeProviderCommand extends Command
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
    protected $signature = 'module:make-provider
                            {name : The service provider name.}
                            {module? : The name of module will be used.}
                            {--master=scaffold : Indicates the master service provider}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new service provider for the specified module.';

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
        $stub   = ($this->option('master') == 'scaffold' ? 'scaffold/' : '') . 'provider';
        $module = workbench()->findOrFail($this->getModuleName());

        return (new Stub('/' . $stub . '.stub', [
            'NAMESPACE'  => $this->getClassNamespace($module),
            'CLASS'      => $this->getClass(),
            'LOWER_NAME' => $module->getLowerName(),
        ]))->render();
    }

    /**
     * Get the destination file path.
     *
     * @return string
     */
    protected function getDestinationFilePath()
    {
        $path          = workbench()->getModulePath($this->getModuleName());
        $generatorPath = workbench()->config('paths.generator.provider');

        return $path . $generatorPath . '/' . $this->getFileName() . '.php';
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    protected function getDefaultNamespace()
    {
        return 'Providers';
    }
}

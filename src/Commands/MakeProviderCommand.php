<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Support\Stub;
use Arcanedev\Workbench\Bases\BenchCommand;
use Arcanedev\Workbench\Traits\ModuleCommandTrait;

/**
 * Class     MakeProviderCommand
 *
 * @package  Arcanedev\Workbench\Commands
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class MakeProviderCommand extends BenchCommand
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

        return Stub::create('/' . $stub . '.stub', [
            'NAMESPACE'  => $this->getClassNamespace($module),
            'CLASS'      => $this->getClass(),
            'LOWER_NAME' => $module->getLowerName(),
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
        return parent::getDestinationFilePath('provider');
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

<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Support\Stub;
use Arcanedev\Workbench\Bases\Command;
use Arcanedev\Workbench\Traits\ModuleCommandTrait;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class MakeRequestCommand
 * @package Arcanedev\Workbench\Commands
 */
class MakeRequestCommand extends Command
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
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new form request class for the specified module.';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the form request class.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $module = workbench()->findOrFail($this->getModuleName());

        return (new Stub('/request.stub', [
            'MODULE'           => $this->getModuleName(),
            'NAME'             => $this->getFileName(),
            'MODULE_NAMESPACE' => workbench()->config('namespace'),
            'NAMESPACE'        => $this->getClassNamespace($module),
            'CLASS'            => $this->getClass(),
        ]))->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path       = workbench()->getModulePath($this->getModuleName());
        $seederPath = workbench()->config('paths.generator.request');

        return $path . $seederPath . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return Str::studly($this->argument('name'));
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace()
    {
        return 'Requests';
    }
}

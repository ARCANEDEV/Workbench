<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Support\Stub;
use Arcanedev\Workbench\Bases\Command;
use Arcanedev\Workbench\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class GenerateRouteProviderCommand
 * @package Arcanedev\Workbench\Commands
 */
class GenerateRouteProviderCommand extends Command
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
     * The command name.
     *
     * @var string
     */
    protected $name = 'module:route-provider';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Generate a new route service provider for the specified module.';

    /**
     * The command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    /**
     * Get template contents.
     *
     * @return string
     */
    protected function getTemplateContents()
    {
        return (new Stub('/route-provider.stub', [
            'MODULE'           => $this->getModuleName(),
            'NAME'             => $this->getFileName(),
            'MODULE_NAMESPACE' => workbench()->config('namespace'),
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
     * @return string
     */
    private function getFileName()
    {
        return 'RouteServiceProvider';
    }
}

<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Support\Stub;
use Arcanedev\Workbench\Bases\BenchCommand;

/**
 * Class     GenerateRouteProviderCommand
 *
 * @package  Arcanedev\Workbench\Commands
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class GenerateRouteProviderCommand extends BenchCommand
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:route-provider
                            {module? : The name of module will be used.}';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Generate a new route service provider for the specified module.';

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
        return Stub::create('/route-provider.stub', [
            'MODULE'           => $this->getModuleName(),
            'NAME'             => $this->getFileName(),
            'MODULE_NAMESPACE' => workbench()->config('namespace'),
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
     * Get file name
     *
     * @return string
     */
    protected function getFileName()
    {
        return 'RouteServiceProvider';
    }
}

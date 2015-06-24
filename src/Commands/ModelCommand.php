<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Support\Stub;
use Arcanedev\Workbench\Bases\BenchCommand;

/**
 * Class ModelCommand
 * @package Arcanedev\Workbench\Commands
 */
class ModelCommand extends BenchCommand
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
    protected $argumentName = 'model';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-model
                            {model : The name of model will be created.}
                            {module? : The name of module will be used.}
                            {--fillable : The fillable attributes.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new model for the specified module.';

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get default namespace.
     *
     * @return string
     */
    protected function getDefaultNamespace()
    {
        return workbench()->config('paths.generator.model');
    }

    /**
     * Get template contents.
     *
     * @return string
     */
    protected function getTemplateContents()
    {
        $module = $this->getModule();

        return Stub::create('/model.stub', [
            'MODULE'           => $this->getModuleName(),
            'NAME'             => $this->getFileName(),
            'FILLABLE'         => $this->getFillable(),
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
        return parent::getDestinationFilePath('model');
    }

    /**
     * Get model name
     *
     * @return string
     */
    protected function getFileName()
    {
        return str_studly($this->getStringArg('model'));
    }

    /**
     * Get fillable
     *
     * @return string
     */
    private function getFillable()
    {
        $fillable = $this->option('fillable');

        if ( ! is_null($fillable)) {
            $arrays = explode(',', $fillable);

            return json_encode($arrays);
        }

        return '[]';
    }
}

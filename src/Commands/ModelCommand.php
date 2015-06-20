<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Support\Stub;
use Arcanedev\Workbench\Bases\Command;
use Arcanedev\Workbench\Traits\ModuleCommandTrait;
use Illuminate\Support\Str;

/**
 * Class ModelCommand
 * @package Arcanedev\Workbench\Commands
 */
class ModelCommand extends Command
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
    protected $argumentName = 'model';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-model
                            {model : The name of model will be created.}
                            {module? :The name of module will be used.}
                            {--fillable : The fillable attributes.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new model for the specified module.';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $module = workbench()->findOrFail($this->getModuleName());

        return (new Stub('/model.stub', [
            'MODULE'           => $this->getModuleName(),
            'NAME'             => $this->getModelName(),
            'FILLABLE'         => $this->getFillable(),
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
        $seederPath = workbench()->config('paths.generator.model');

        return $path . $seederPath . '/' . $this->getModelName() . '.php';
    }

    /**
     * @return mixed|string
     */
    private function getModelName()
    {
        return Str::studly($this->argument('model'));
    }

    /**
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

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace()
    {
        return workbench()->config('paths.generator.model');
    }
}

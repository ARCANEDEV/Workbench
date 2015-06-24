<?php namespace Arcanedev\Workbench\Bases;

use Arcanedev\Workbench\Entities\Module;
use Arcanedev\Workbench\Exceptions\FileAlreadyExistException;
use Arcanedev\Workbench\Exceptions\InvalidFileNameException;
use Arcanedev\Workbench\Generators\FileGenerator;
use Arcanedev\Workbench\Traits\ModuleCommandTrait;

/**
 * Class BenchCommand
 * @package Arcanedev\Workbench\Bases
 */
abstract class BenchCommand extends Command
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
     * The name of 'name' argument.
     *
     * @var string
     */
    protected $argumentName = '';

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get template contents.
     *
     * @return string
     */
    abstract protected function getTemplateContents();

    /**
     * Get the destination file path.
     *
     * @param  string $name
     *
     * @throws InvalidFileNameException
     *
     * @return string
     */
    protected function getDestinationFilePath($name = '')
    {
        $modulePath = workbench()->getModulePath($this->getModuleName());
        $filePath   = workbench()->config('paths.generator.' . $name, '');

        return $modulePath . $filePath . '/' . $this->getFileName() . '.php';
    }

    /**
     * Get class name.
     *
     * @return string
     */
    protected function getClass()
    {
        return is_string($name = $this->argument($this->argumentName))
            ? class_basename($name)
            : '';
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    protected function getDefaultNamespace()
    {
        return '';
    }

    /**
     * Get studly file name
     *
     * @throws InvalidFileNameException
     *
     * @return string
     */
    protected function getFileName()
    {
        if (is_string($name = $this->argument('name'))) {
            return str_studly($name);
        }

        throw new InvalidFileNameException;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->runCommand();
    }

    /**
     * Run the generate console command.
     */
    private function runCommand()
    {
        $path = str_replace('\\', '/', $this->getDestinationFilePath());

        if ( ! app('files')->isDirectory($dir = dirname($path))) {
            app('files')->makeDirectory($dir, 0777, true);
        }

        $contents = $this->getTemplateContents();

        try {
            (new FileGenerator($path, $contents))->generate();

            $this->info("Created : {$path}");
        }
        catch (FileAlreadyExistException $e) {
            $this->error("File : {$path} already exists.");
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get class namespace.
     *
     * @param  Module $module
     *
     * @return string
     */
    protected function getClassNamespace(Module $module)
    {
        $extra      = str_replace($this->getClass(), '', $this->argument($this->argumentName));

        return rtrim(implode('\\', [
            config('workbench.namespace'),
            $module->getStudlyName(),
            $this->getDefaultNamespace(),
            str_replace('/', '\\', $extra),
        ]), '\\');
    }
}

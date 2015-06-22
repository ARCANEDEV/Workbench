<?php namespace Arcanedev\Workbench\Bases;

use Arcanedev\Workbench\Entities\Module;
use Arcanedev\Workbench\Exceptions\FileAlreadyExistException;
use Arcanedev\Workbench\Exceptions\InvalidFileNameException;
use Arcanedev\Workbench\Generators\FileGenerator;
use Illuminate\Console\Command as IlluminateCommand;

/**
 * Class Command
 * @package Arcanedev\Workbench\Bases
 */
abstract class Command extends IlluminateCommand
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
    protected $signature = '';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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
     * @return string
     */
    abstract protected function getDestinationFilePath();

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

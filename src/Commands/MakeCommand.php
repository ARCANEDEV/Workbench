<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Workbench\Generators\ModuleGenerator;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class MakeCommand
 * @package Arcanedev\Workbench\Commands
 */
class MakeCommand extends Command
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new module.';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $names = $this->argument('name');

        foreach ($names as $name) {
            (new ModuleGenerator($name))
                ->setFilesystem(app('files'))
                ->setWorkbench(workbench())
                ->setConfig(config())
                ->setConsole($this)
                ->setForce($this->option('force'))
                ->setPlain($this->option('plain'))
                ->generate();
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::IS_ARRAY, 'The names of modules will be created.'],
        ];
    }

    protected function getOptions()
    {
        return [
            ['plain', 'p', InputOption::VALUE_NONE, 'Generate a plain module (without some resources).'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when module already exist.'],
        ];
    }
}

<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Workbench\Generators\ModuleGenerator;
use Illuminate\Console\Command;

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
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make
                            {name: The names of modules will be created.}
                            {--plain? : Generate a plain module (without some resources).}
                            {--force? : Force the operation to run when module already exist.}';

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
    public function handle()
    {
        $names = $this->argument();

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
}

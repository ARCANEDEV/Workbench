<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Workbench\Bases\Command;
use Arcanedev\Workbench\Generators\ModuleGenerator;

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
                            {names : The names of modules will be created (Comma-separated names).}
                            {--plain : Generate a plain module (without some resources).}
                            {--force : Force the operation to run when module already exist.}';

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
        foreach ($this->getNames() as $name) {
            (new ModuleGenerator($name))
                ->setFilesystem(app('files'))
                ->setWorkbench(workbench())
                ->setConfig(config())
                ->setConsole($this)
                ->setForce($this->option('force'))
                ->setPlain($this->option('plain'))
                ->generate();
        }

        $this->line("<comment>Dump all modules autoload</comment>");

        chdir(base_path());
        passthru('composer dump -o -n -q');
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get names argument
     *
     * @return array
     */
    private function getNames()
    {
        $names = array_map('trim', explode(',', $this->argument('names')));

        return $names;
    }
}

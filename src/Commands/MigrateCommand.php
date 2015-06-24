<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Workbench\Bases\Command;
use Arcanedev\Workbench\Entities\Module;

/**
 * Class MigrateCommand
 * @package Arcanedev\Workbench\Commands
 */
class MigrateCommand extends Command
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
    protected $signature = 'module:migrate
                            {module? : The name of module will be used.}
                            {--db= : The database connection to use.}
                            {--dir=asc : The direction of ordering.}
                            {--force : Force the operation to run when in production.}
                            {--pretend : Dump the SQL queries that would be run.}
                            {--seed : Indicates if the seed task should be re-run.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate the migrations from the specified module or from all modules.';

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get migration path for specific module.
     *
     * @param  Module $module
     *
     * @return string
     */
    private function getPath(Module $module)
    {
        $path = $module->getExtraPath(config('modules.paths.generator.migration'));

        return str_replace(base_path(), '', $path);
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
        $name = $this->getStringArg('module');

        if ($name) {
            $this->migrate($name);

            return;
        }

        $modules = workbench()->getOrdered($this->getStringOption('dir'));

        foreach ($modules as $module) {
            /** @var Module $module */
            $this->line('Running for module: <info>' . $module->getName() . '</info>');
            $this->migrate($module);
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Run the migration from the specified module.
     *
     * @param string $name
     */
    private function migrate($name)
    {
        $module = workbench()->findOrFail($name);

        $this->call('migrate', [
            '--path'     => $this->getPath($module),
            '--database' => $this->getStringOption('db'),
            '--pretend'  => $this->getBoolOption('pretend'),
            '--force'    => $this->getBoolOption('force'),
        ]);

        if ($this->getBoolOption('seed')) {
            $this->call('module:seed', ['module' => $name]);
        }
    }
}

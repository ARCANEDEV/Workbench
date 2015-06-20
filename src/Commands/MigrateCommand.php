<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Workbench\Entities\Module;
use Illuminate\Console\Command;

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
                            {--dir=asc : The direction of ordering.}
                            {--db? : The database connection to use.}
                            {--pretend? : Dump the SQL queries that would be run.}
                            {--force? : Force the operation to run when in production.}
                            {--seed? : Indicates if the seed task should be re-run.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate the migrations from the specified module or from all modules.';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->module = $this->laravel['modules'];
        $name = $this->argument('module');

        if ($name) {
            $this->migrate($name);

            return;
        }

        foreach (workbench()->getOrdered($this->option('direction')) as $module) {
            /** @var Module $module */
            $this->line('Running for module: <info>' . $module->getName() . '</info>');
            $this->migrate($module);
        }
    }

    /**
     * Run the migration from the specified module.
     *
     * @param string $name
     */
    protected function migrate($name)
    {
        $module = workbench()->findOrFail($name);

        $this->call('migrate', [
            '--path'     => $this->getPath($module),
            '--database' => $this->option('db'),
            '--pretend'  => $this->option('pretend'),
            '--force'    => $this->option('force'),
        ]);

        if ($this->option('seed')) {
            $this->call('module:seed', ['module' => $name]);
        }
    }

    /**
     * Get migration path for specific module.
     *
     * @param  Module $module
     *
     * @return string
     */
    protected function getPath(Module $module)
    {
        $path = $module->getExtraPath(config('modules.paths.generator.migration'));

        return str_replace(base_path(), '', $path);
    }
}

<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Workbench\Entities\Module;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

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
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:migrate';

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
    public function fire()
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
            '--database' => $this->option('database'),
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

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['direction', 'd', InputOption::VALUE_OPTIONAL, 'The direction of ordering.', 'asc'],
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'],
            ['pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run.'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'],
            ['seed', null, InputOption::VALUE_NONE, 'Indicates if the seed task should be re-run.'],
        ];
    }
}

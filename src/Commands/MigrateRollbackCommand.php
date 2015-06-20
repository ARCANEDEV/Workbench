<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Workbench\Entities\Module;
use Arcanedev\Workbench\Services\Migrator;
use Arcanedev\Workbench\Traits\MigrationLoaderTrait;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class MigrateRollbackCommand
 * @package Arcanedev\Workbench\Commands
 */
class MigrateRollbackCommand extends Command
{
    /* ------------------------------------------------------------------------------------------------
     |  Traits
     | ------------------------------------------------------------------------------------------------
     */
    use MigrationLoaderTrait;

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:migrate-rollback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback the modules migrations.';

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
        $module = $this->argument('module');

        if ( ! empty($module)) {
            $this->rollback($module);
            return;
        }

        foreach (workbench()->all() as $module) {
            /** @var Module $module */
            $this->line('Running for module: <info>' . $module->getName() . '</info>');
            $this->rollback($module);
        }
    }

    /**
     * Rollback migration from the specified module.
     *
     * @param $module
     */
    public function rollback($module)
    {
        if (is_string($module)) {
            $module = workbench()->findOrFail($module);
        }

        $migrator = new Migrator($module);
        $migrated = $migrator->rollback();

        if (count($migrated)) {
            foreach ($migrated as $migration) {
                $this->line("Rollback: <info>{$migration}</info>");
            }
            return;
        }

        $this->comment('Nothing to rollback.');
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
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'],
            ['pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run.'],
        ];
    }
}

<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Workbench\Entities\Module;
use Arcanedev\Workbench\Services\Migrator;
use Arcanedev\Workbench\Traits\MigrationLoaderTrait;
use Illuminate\Console\Command;

/**
 * Class MigrateResetCommand
 * @package Arcanedev\Workbench\Commands
 */
class MigrateResetCommand extends Command
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
     * The name and signature of the console command.
     * @todo: check the signature options
     *
     * @var string
     */
    protected $signature = 'module:migrate-reset
                            {module? : The name of module will be used.}
                            {--db= : The database connection to use.}
                            {--force : Force the operation to run when in production.}
                            {--pretend : Dump the SQL queries that would be run.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the modules migrations.';

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
        $module = $this->argument('module');

        if ( ! empty($module)) {
            $this->reset($module);
            return;
        }

        foreach (workbench()->all() as $module) {
            /** @var Module $module */
            $this->line('Running for module: <info>' . $module->getName() . '</info>');
            $this->reset($module);
        }
    }

    /**
     * Rollback migration from the specified module.
     *
     * @param $module
     */
    public function reset($module)
    {
        if (is_string($module)) {
            $module = workbench()->findOrFail($module);
        }

        $migrated = (new Migrator($module))->reset();

        if (count($migrated)) {
            foreach ($migrated as $migration) {
                $this->line("Rollback: <info>{$migration}</info>");
            }
            return;
        }

        $this->comment('Nothing to rollback.');
    }
}

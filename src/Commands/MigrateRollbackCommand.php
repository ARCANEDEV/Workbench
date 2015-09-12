<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Workbench\Bases\Command;
use Arcanedev\Workbench\Entities\Module;
use Arcanedev\Workbench\Services\Migrator;
use Arcanedev\Workbench\Traits\MigrationLoaderTrait;

/**
 * Class     MigrateRollbackCommand
 *
 * @package  Arcanedev\Workbench\Commands
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
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
     * The name and signature of the console command.
     * @todo: check options
     *
     * @var string
     */
    protected $signature = 'module:migrate-rollback
                            {module? : The name of module will be used.}
                            {--db= : The database connection to use.}
                            {--force : Force the operation to run when in production.}
                            {--pretend : Dump the SQL queries that would be run.}';

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
     */
    public function handle()
    {
        if ( ! empty($module = $this->getStringArg('module'))) {
            $this->rollback($module);

            return;
        }

        foreach (workbench()->all() as $module) {
            $this->rollback($module);
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Rollback migration from the specified module.
     *
     * @param Module|string $module
     */
    private function rollback($module)
    {
        if (is_string($module)) {
            $module = workbench()->findOrFail($module);
        }

        $this->line('Running for module: <info>' . $module->getName() . '</info>');
        $migrated = (new Migrator($module))->rollback();

        if (count($migrated)) {
            foreach ($migrated as $migration) {
                $this->line("Rollback: <info>{$migration}</info>");
            }
        }
        else {
            $this->comment('Nothing to rollback.');
        }
    }
}

<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Workbench\Bases\Command;
use Arcanedev\Workbench\Entities\Module;
use Arcanedev\Workbench\Services\Migrator;
use Arcanedev\Workbench\Traits\MigrationLoaderTrait;

/**
 * Class     MigrateResetCommand
 *
 * @package  Arcanedev\Workbench\Commands
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
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
        if ( ! empty($module = $this->getStringArg('module'))) {
            $this->reset($module);

            return;
        }

        foreach (workbench()->all() as $module) {
            $this->reset($module);
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
    private function reset($module)
    {
        if (is_string($module)) {
            $module = workbench()->findOrFail($module);
        }

        $this->line('Running for module: <info>' . $module->getName() . '</info>');

        $migrated = (new Migrator($module))->reset();

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

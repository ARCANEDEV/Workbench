<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Workbench\Traits\ModuleCommandTrait;
use Illuminate\Console\Command;

/**
 * Class MigrateRefreshCommand
 * @package Arcanedev\Workbench\Commands
 */
class MigrateRefreshCommand extends Command
{
    /* ------------------------------------------------------------------------------------------------
     |  Traits
     | ------------------------------------------------------------------------------------------------
     */
    use ModuleCommandTrait;

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:migrate-refresh
                            {module? : The name of module will be used.}
                            {--db? : The database connection to use.}
                            {--force? : Force the operation to run when in production.}
                            {--seed? : Indicates if the seed task should be re-run.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback & re-migrate the modules migrations.';

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
        $this->call('module:migrate-reset', [
            'module'     => $this->getModuleName(),
            '--database' => $this->option('db'),
            '--force'    => $this->option('force'),
        ]);

        $this->call('module:migrate', [
            'module'     => $this->getModuleName(),
            '--database' => $this->option('db'),
            '--force'    => $this->option('force'),
        ]);

        if ($this->option('seed')) {
            $this->call('module:seed', [
                'module' => $this->getModuleName(),
            ]);
        }
    }
}

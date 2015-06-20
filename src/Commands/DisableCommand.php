<?php namespace Arcanedev\Workbench\Commands;

use Illuminate\Console\Command;

/**
 * Class DisableCommand
 * @package Arcanedev\Workbench\Commands
 */
class DisableCommand extends Command
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
    protected $signature = 'module:disable
                            {module : The Module name.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable the specified module.';

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
        $module = workbench()->findOrFail($this->argument('module'));

        if ($module->enabled()) {
            $module->disable();
            $this->info("Module [{$module}] disabled successful.");
        }
        else {
            $this->comment("Module [{$module}] has already disabled.");
        }
    }
}

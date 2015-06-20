<?php namespace Arcanedev\Workbench\Commands;

use Illuminate\Console\Command;

/**
 * Class EnableCommand
 * @package Arcanedev\Workbench\Commands
 */
class EnableCommand extends Command
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
    protected $signature = 'module:enable
                            {module : The Module name.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable the specified module.';

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

        if ($module->disabled()) {
            $module->enable();
            $this->info("Module [{$module}] enabled successful.");
        }
        else {
            $this->comment("Module [{$module}] has already enabled.");
        }
    }
}

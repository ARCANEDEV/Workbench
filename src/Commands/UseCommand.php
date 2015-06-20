<?php namespace Arcanedev\Workbench\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

/**
 * Class UseCommand
 * @package Arcanedev\Workbench\Commands
 */
class UseCommand extends Command
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
    protected $signature = 'module:use
                            {module? : The name of module will be used.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Use the specified module.';

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
        $module = Str::studly($this->argument('module'));
        if ( ! workbench()->has($module)) {
            $this->error("Module [{$module}] does not exists.");
            return;
        }

        workbench()->setUsed($module);
        $this->info("Module [{$module}] used successfully.");
    }
}

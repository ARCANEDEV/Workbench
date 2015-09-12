<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Workbench\Bases\Command;

/**
 * Class     UseCommand
 *
 * @package  Arcanedev\Workbench\Commands
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
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
        $module = str_studly($this->getStringArg('module'));

        if ( ! workbench()->has($module)) {
            workbench()->setUsed($module);
            $this->info("Module [{$module}] used successfully.");
        }
        else {
            $this->error("Module [{$module}] does not exists.");
        }
    }
}

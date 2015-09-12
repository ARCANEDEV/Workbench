<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Workbench\Bases\Command;
use Arcanedev\Workbench\Traits\ModuleCommandTrait;

/**
 * Class     DisableCommand
 *
 * @package  Arcanedev\Workbench\Commands
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class DisableCommand extends Command
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
     */
    public function handle()
    {
        $module = $this->getModule();

        if ($module->enabled()) {
            $module->disable();
            $this->info("Module [{$module}] disabled successful.");
        }
        else {
            $this->comment("Module [{$module}] has already disabled.");
        }
    }
}

<?php namespace Arcanedev\Workbench\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

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
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:enable';

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
    public function fire()
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

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::REQUIRED, 'Module name.'],
        ];
    }
}

<?php namespace Arcanedev\Workbench\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

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
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:use';

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
    public function fire()
    {
        $module = Str::studly($this->argument('module'));
        if ( ! workbench()->has($module)) {
            $this->error("Module [{$module}] does not exists.");
            return;
        }

        workbench()->setUsed($module);
        $this->info("Module [{$module}] used successfully.");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::REQUIRED, 'The name of module will be used.'],
        ];
    }
}

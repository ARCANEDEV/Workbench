<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Workbench\Entities\Module;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class DumpCommand
 * @package Arcanedev\Workbench\Commands
 */
class DumpCommand extends Command
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
    protected $name = 'module:dump';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dump-autoload the specified module or for all module.';

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
        $this->info('Generating optimized autoload modules.');

        if ($module = $this->argument('module')) {
            $this->dump($module);
        }
        else {
            foreach (workbench()->all() as $module) {
                /** @var Module $module */
                $this->dump($module->getStudlyName());
            }
        }
    }

    /**
     * @param string $module
     */
    public function dump($module)
    {
        $module = workbench()->findOrFail($module);

        $this->line("<comment>Running for module</comment>: {$module}");
        chdir($module->getPath());
        passthru('composer dump -o -n -q');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::OPTIONAL, 'Module name.'],
        ];
    }
}

<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Workbench\Entities\Module;
use Arcanedev\Workbench\Publishers\MigrationPublisher;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class PublishMigrationCommand
 * @package Arcanedev\Workbench\Commands
 */
class PublishMigrationCommand extends Command
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
    protected $name = 'module:publish-migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Publish a module's migrations to the application";

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Execute the console command.
     */
    public function fire()
    {
        if ($name = $this->argument('module')) {
            $module = workbench()->findOrFail($name);
            $this->publish($module);

            return;
        }

        foreach (workbench()->enabled() as $module) {
            $this->publish($module);
        }
    }

    /**
     * Publish migration for the specified module.
     *
     * @param Module $module
     */
    public function publish(Module $module)
    {
        (new MigrationPublisher($module))
            ->setWorkbench(workbench())
            ->setConsole($this)
            ->publish();
    }
    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::OPTIONAL, 'The name of module being used.'],
        ];
    }
}

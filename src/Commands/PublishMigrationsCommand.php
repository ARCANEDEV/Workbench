<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Workbench\Bases\Command;
use Arcanedev\Workbench\Entities\Module;
use Arcanedev\Workbench\Publishers\MigrationPublisher;

/**
 * Class     PublishMigrationsCommand
 *
 * @package  Arcanedev\Workbench\Commands
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class PublishMigrationsCommand extends Command
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
    protected $signature = 'module:publish-migrations
                            {module? : The name of module being used.}';

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
    public function handle()
    {
        if ($name = $this->getStringArg('module')) {
            $module = workbench()->findOrFail($name);
            $this->publish($module);

            return;
        }

        foreach (workbench()->enabled() as $module) {
            $this->publish($module);
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Publish migration for the specified module.
     *
     * @param Module $module
     */
    private function publish(Module $module)
    {
        (new MigrationPublisher($module))
            ->setWorkbench(workbench())
            ->setConsole($this)
            ->publish();
    }
}

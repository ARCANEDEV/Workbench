<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Workbench\Bases\Command;
use Arcanedev\Workbench\Entities\Module;
use Arcanedev\Workbench\Publishers\LangPublisher;

/**
 * Class     PublishTranslationCommand
 *
 * @package  Arcanedev\Workbench\Commands
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class PublishTranslationCommand extends Command
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
    protected $signature = 'module:publish-translations
                            {module? : The name of module will be used.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish a module\'s translations to the application';

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
            $this->publish($name);
        }
        else {
            $this->publishAll();
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Publish assets from all modules.
     */
    private function publishAll()
    {
        /** @var Module $module */
        foreach (workbench()->enabled() as $module) {
            $this->publish($module);
        }
    }

    /**
     * Publish assets from the specified module.
     *
     * @param Module|string $name
     */
    private function publish($name)
    {
        $module = $name instanceof Module
            ? $name
            : workbench()->findOrFail($name);

        (new LangPublisher($module))
            ->setWorkbench(workbench())
            ->setConsole($this)
            ->publish();

        $this->line("<info>Published</info>: {$module->getStudlyName()}");
    }
}

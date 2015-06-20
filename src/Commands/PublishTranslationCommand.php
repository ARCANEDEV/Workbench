<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Workbench\Entities\Module;
use Arcanedev\Workbench\Publishers\LangPublisher;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class PublishTranslationCommand
 * @package Arcanedev\Workbench\Commands
 */
class PublishTranslationCommand extends Command
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
    protected $name = 'module:publish-translation';

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
    public function fire()
    {
        if ($name = $this->argument('module')) {
            $this->publish($name);
        }
        else {
            $this->publishAll();
        }
    }

    /**
     * Publish assets from all modules.
     */
    public function publishAll()
    {
        foreach (workbench()->enabled() as $module) {
            $this->publish($module);
        }
    }

    /**
     * Publish assets from the specified module.
     *
     * @param string $name
     */
    public function publish($name)
    {
        if ($name instanceof Module) {
            $module = $name;
        }
        else {
            $module = workbench()->findOrFail($name);
        }

        (new LangPublisher($module))
            ->setWorkbench(workbench())
            ->setConsole($this)
            ->publish();

        $this->line("<info>Published</info>: {$module->getStudlyName()}");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }
}

<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Support\Stub;
use Arcanedev\Workbench\Bases\Command;

/**
 * Class MakeConsoleCommand
 * @package Arcanedev\Workbench\Commands
 */
class MakeConsoleCommand extends Command
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The name of argument name.
     *
     * @var string
     */
    protected $argumentName = 'name';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-command
                            {name : The name of the command.}
                            {module? : The name of module will be used.}
                            {--queue : The terminal command that should be assigned.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new Artisan command for the specified module.';

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get template contents
     *
     * @return mixed
     */
    protected function getTemplateContents()
    {
        return Stub::create('/command.stub', [
            'COMMAND_NAME'  => $this->getCommandName(),
            'NAMESPACE'     => $this->getClassNamespace($this->getModule()),
            'CLASS'         => $this->getClass(),
        ])->render();
    }

    /**
     * Get the destination file path.
     *
     * @param  string $name
     *
     * @return string
     */
    protected function getDestinationFilePath($name = '')
    {
        return parent::getDestinationFilePath('command');
    }

    /**
     * Get command name
     *
     * @return string
     */
    private function getCommandName()
    {
        return $this->option('command') ?: 'command:name';
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    protected function getDefaultNamespace()
    {
        return 'Commands';
    }
}

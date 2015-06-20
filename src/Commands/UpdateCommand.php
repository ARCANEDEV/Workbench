<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Workbench\Traits\ModuleCommandTrait;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class UpdateCommand
 * @package Arcanedev\Workbench\Commands
 */
class UpdateCommand extends Command
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
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update dependencies for the specified module or for all modules.';

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
        workbench()->update($name = $this->getModuleName());

        $this->info("Module [{$name}] updated successfully.");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::OPTIONAL, 'The name of module will be updated.'],
        ];
    }
}

<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Support\Stub;
use Arcanedev\Workbench\Bases\Command;

/**
 * Class SeedMakeCommand
 * @package Arcanedev\Workbench\Commands
 */
class SeedMakeCommand extends Command
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
    protected $signature = 'module:make-seed
                            {name : The name of seeder will be created.}
                            {module? : The name of module will be used.}
                            {--master : Indicates the seeder will created is a master database seeder.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new seeder for the specified module.';

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get template contents.
     *
     * @return string
     */
    protected function getTemplateContents()
    {
        return Stub::create('/seeder.stub', [
            'NAME'             => $this->getFileName(),
            'MODULE'           => $this->getModuleName(),
            'MODULE_NAMESPACE' => workbench()->config('namespace'),
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
        return parent::getDestinationFilePath('seeder');
    }

    /**
     * Get seeder name.
     *
     * @return string
     */
    protected function getFileName()
    {
        $end = $this->option('master') ? 'DatabaseSeeder' : 'TableSeeder';

        return parent::getFileName() . $end;
    }
}

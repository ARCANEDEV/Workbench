<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Support\Stub;
use Arcanedev\Workbench\Bases\Command;
use Arcanedev\Workbench\Traits\ModuleCommandTrait;

/**
 * Class SeedMakeCommand
 * @package Arcanedev\Workbench\Commands
 */
class SeedMakeCommand extends Command
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
     * @return string
     */
    protected function getDestinationFilePath()
    {
        $path       = workbench()->getModulePath($this->getModuleName());
        $seederPath = workbench()->config('paths.generator.seeder');

        return $path . $seederPath . '/' . $this->getFileName() . '.php';
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

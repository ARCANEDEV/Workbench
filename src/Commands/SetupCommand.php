<?php namespace Arcanedev\Workbench\Commands;

use Illuminate\Console\Command;

/**
 * Class SetupCommand
 * @package Arcanedev\Workbench\Commands
 */
class SetupCommand extends Command
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
    protected $name = 'module:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setting up modules folders for first use.';

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
        $this->generateModulesFolder();
        $this->generateAssetsFolder();
    }

    /**
     * Generate the modules folder.
     */
    public function generateModulesFolder()
    {
        $this->generateDirectory(workbench()->config('paths.modules'),
            'Modules directory created successfully',
            'Modules directory already exist'
        );
    }

    /**
     * Generate the assets folder.
     */
    public function generateAssetsFolder()
    {
        $this->generateDirectory(workbench()->config('paths.assets'),
            'Assets directory created successfully',
            'Assets directory already exist'
        );
    }

    /**
     * Generate the specified directory by given $dir.
     *
     * @param $dir
     * @param $success
     * @param $error
     */
    protected function generateDirectory($dir, $success, $error)
    {
        if ( ! app('files')->isDirectory($dir)) {
            app('files')->makeDirectory($dir);
            $this->info($success);

            return;
        }

        $this->error($error);
    }
}

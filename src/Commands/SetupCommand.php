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
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:setup';

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
    public function handle()
    {
        $this->generateModulesFolder();
        $this->generateAssetsFolder();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Generate the modules folder.
     */
    private function generateModulesFolder()
    {
        $this->generateDirectory(workbench()->config('paths.modules'),
            'Modules directory created successfully',
            'Modules directory already exist'
        );
    }

    /**
     * Generate the assets folder.
     */
    private function generateAssetsFolder()
    {
        $this->generateDirectory(workbench()->config('paths.assets'),
            'Assets directory created successfully',
            'Assets directory already exist'
        );
    }

    /**
     * Generate the specified directory by given $dir.
     *
     * @param string $dir
     * @param string $success
     * @param string $error
     */
    private function generateDirectory($dir, $success, $error)
    {
        if ( ! app('files')->isDirectory($dir)) {
            app('files')->makeDirectory($dir);
            $this->info($success);

            return;
        }

        $this->error($error);
    }
}

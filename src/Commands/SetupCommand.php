<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Workbench\Helpers\ComposerFile;
use Exception;
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
        $this->updateComposer();

        try {
            $this->makeModulesFolder();
            $this->makeAssetsFolder();
        }
        catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Update the main composer to autoload the modules.
     */
    private function updateComposer()
    {
        $composer = new ComposerFile();

        if ( ! $composer->hasMergeModules()) {
            $composer->addMergeModules();
            $this->info('Composer file updated...');
        }
    }

    /**
     * Generate the modules folder.
     */
    private function makeModulesFolder()
    {
        $this->generateDirectory(workbench()->config('paths.modules'),
            'Modules directory created successfully',
            'Modules directory already exist'
        );
    }

    /**
     * Generate the assets folder.
     */
    private function makeAssetsFolder()
    {
        $this->generateDirectory(workbench()->config('paths.assets'),
            'Assets directory created successfully',
            'Assets directory already exist'
        );
    }

    /**
     * Generate the specified directory by given $dir.
     *
     * @param  string $dir
     * @param  string $success
     * @param  string $error
     *
     * @throws Exception
     */
    private function generateDirectory($dir, $success, $error)
    {
        if (app('files')->isDirectory($dir)) {
            throw new Exception($error);
        }

        app('files')->makeDirectory($dir);
        $this->info($success);
    }
}

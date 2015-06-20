<?php namespace Arcanedev\Workbench\Traits;

trait MigrationLoaderTrait
{
    /**
     * Include all migrations files from the specified module.
     *
     * @param string $module
     */
    protected function loadMigrationFiles($module)
    {
        $path  = workbench()->getModulePath($module) . $this->getMigrationGeneratorPath();
        $files = app('files')->glob($path . '/*_*.php');

        foreach ($files as $file) {
            app('files')->requireOnce($file);
        }
    }

    /**
     * Get migration generator path.
     *
     * @return string
     */
    protected function getMigrationGeneratorPath()
    {
        return workbench()->config('paths.generator.migration');
    }
}

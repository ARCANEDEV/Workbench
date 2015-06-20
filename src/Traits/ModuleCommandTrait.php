<?php namespace Arcanedev\Workbench\Traits;
use Arcanedev\Workbench\Entities\Module;

/**
 * Trait ModuleCommandTrait
 * @package Arcanedev\Workbench\Traits
 */
trait ModuleCommandTrait
{
    /**
     * Get the module name.
     *
     * @return string
     */
    public function getModuleName()
    {
        $module = $this->argument('module') ?: $this->laravel['modules']->getUsedNow();

        /** @var Module $module */
        $module = $this->laravel['modules']->findOrFail($module);

        return $module->getStudlyName();
    }
}

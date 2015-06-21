<?php namespace Arcanedev\Workbench\Traits;
use Arcanedev\Workbench\Entities\Module;
use Illuminate\Console\Command;

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
        /** @var Command $this */
        $module = $this->argument('module') ?: workbench()->getUsedNow();

        /** @var Module $module */
        $module = workbench()->findOrFail($module);

        return $module->getStudlyName();
    }
}

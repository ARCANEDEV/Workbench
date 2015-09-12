<?php namespace Arcanedev\Workbench\Traits;

use Arcanedev\Workbench\Bases\Command;
use Arcanedev\Workbench\Entities\Module;
use Arcanedev\Workbench\Exceptions\ModuleNotFoundException;

/**
 * Trait     ModuleCommandTrait
 *
 * @package  Arcanedev\Workbench\Traits
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
trait ModuleCommandTrait
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get module
     *
     * @throws ModuleNotFoundException
     *
     * @return Module
     */
    protected function getModule()
    {
        /** @var Command $this */
        $module = $this->argument('module') ?: workbench()->getUsedNow();

        /** @var string $module */
        return workbench()->findOrFail($module);
    }

    /**
     * Get the module name.
     *
     * @return string
     */
    protected function getModuleName()
    {
        return $this->getModule()->getStudlyName();
    }
}

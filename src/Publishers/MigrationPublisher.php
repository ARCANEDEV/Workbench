<?php namespace Arcanedev\Workbench\Publishers;

use Arcanedev\Workbench\Bases\Publisher;

class MigrationPublisher extends Publisher
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get destination path.
     *
     * @return string
     */
    public function getDestinationPath()
    {
        return $this->workbench->config('paths.migration');
    }

    /**
     * Get source path.
     *
     * @return string
     */
    public function getSourcePath()
    {
        return $this->getModule()->getExtraPath($this->workbench->config('paths.generator.migration'));
    }
}

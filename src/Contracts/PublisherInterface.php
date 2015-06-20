<?php namespace Arcanedev\Workbench\Contracts;

/**
 * Interface PublisherInterface
 * @package Arcanedev\Workbench\Contracts
 */
interface PublisherInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Publish something.
     *
     * @return mixed
     */
    public function publish();
}

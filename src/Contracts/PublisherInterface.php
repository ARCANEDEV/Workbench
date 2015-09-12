<?php namespace Arcanedev\Workbench\Contracts;

/**
 * Interface  PublisherInterface
 *
 * @package   Arcanedev\Workbench\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
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

<?php namespace Arcanedev\Workbench\Contracts;

/**
 * Interface RunableInterface
 * @package Arcanedev\Workbench\Contracts
 */
interface RunableInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Run the specified command.
     *
     * @param string $command
     *
     * @return mixed
     */
    public function run($command);
}

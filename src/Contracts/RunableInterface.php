<?php namespace Arcanedev\Workbench\Contracts;

/**
 * Interface  RunableInterface
 *
 * @package   Arcanedev\Workbench\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
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
     * @return mixed|void
     */
    public function run($command);
}

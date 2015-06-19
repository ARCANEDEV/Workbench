<?php namespace Arcanedev\Workbench\Process;

use Arcanedev\Workbench\Contracts\RunableInterface;
use Arcanedev\Workbench\Workbench;

/**
 * Class Runner
 * @package Arcanedev\Workbench\Process
 */
class Runner implements RunableInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The module instance.
     *
     * @var Workbench
     */
    protected $module;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The constructor.
     *
     * @param Workbench $module
     */
    public function __construct(Workbench $module)
    {
        $this->module = $module;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Run the given command.
     *
     * @param string $command
     */
    public function run($command)
    {
        passthru($command);
    }
}

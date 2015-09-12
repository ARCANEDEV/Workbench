<?php namespace Arcanedev\Workbench\Process;

use Arcanedev\Workbench\Contracts\RunableInterface;
use Arcanedev\Workbench\Workbench;

/**
 * Class     Runner
 *
 * @package  Arcanedev\Workbench\Process
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
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
     * @param  Workbench  $module
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
     * @param  string  $command
     *
     * @return mixed|void
     */
    public function run($command)
    {
        passthru($command);
    }
}

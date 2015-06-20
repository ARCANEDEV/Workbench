<?php namespace Arcanedev\Workbench\Bases;

use Arcanedev\Workbench\Contracts\PublisherInterface;
use Arcanedev\Workbench\Entities\Module;
use Arcanedev\Workbench\Workbench;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use RuntimeException;

abstract class Publisher implements PublisherInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The name of module will used.
     *
     * @var string
     */
    protected $module;

    /**
     * The modules repository instance.
     *
     * @var Workbench
     */
    protected $workbench;

    /**
     * The laravel console instance.
     *
     * @var Command
     */
    protected $console;

    /**
     * The success message will displayed at console.
     *
     * @var string
     */
    protected $success;

    /**
     * The error message will displayed at console.
     *
     * @var string
     */
    protected $error = '';

    /**
     * Determine whether the result message will shown in the console.
     *
     * @var bool
     */
    protected $showMessage = true;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The constructor.
     *
     * @param Module $module
     */
    public function __construct(Module $module)
    {
        $this->module = $module;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Show the result message.
     *
     * @return self
     */
    public function showMessage()
    {
        $this->showMessage = true;

        return $this;
    }

    /**
     * Hide the result message.
     *
     * @return self
     */
    public function hideMessage()
    {
        $this->showMessage = false;

        return $this;
    }

    /**
     * Get module instance.
     *
     * @return Module
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Set modules repository instance.
     *
     * @param  Workbench $workbench
     *
     * @return self
     */
    public function setWorkbench(Workbench $workbench)
    {
        $this->workbench = $workbench;

        return $this;
    }

    /**
     * Get modules repository instance.
     *
     * @return Workbench
     */
    public function getWorkbench()
    {
        return $this->workbench;
    }

    /**
     * Set console instance.
     *
     * @param  Command $console
     *
     * @return self
     */
    public function setConsole(Command $console)
    {
        if ($this->console instanceof Command) {
            $this->console = $console;

            return $this;
        }

        throw new RuntimeException(
            "The 'console' property must instance of \\Illuminate\\Console\\Command."
        );
    }

    /**
     * Get console instance.
     *
     * @return Command
     */
    public function getConsole()
    {
        return $this->console;
    }

    /**
     * Get laravel filesystem instance.
     *
     * @return Filesystem
     */
    public function getFilesystem()
    {
        return $this->workbench->getFiles();
    }

    /**
     * Get destination path.
     *
     * @return string
     */
    abstract public function getDestinationPath();

    /**
     * Get source path.
     *
     * @return string
     */
    abstract public function getSourcePath();

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Publish something.
     */
    public function publish()
    {
        if ( ! $this->getFilesystem()->isDirectory($sourcePath = $this->getSourcePath())) {
            return;
        }

        if ( ! $this->getFilesystem()->isDirectory($destinationPath = $this->getDestinationPath())) {
            $this->getFilesystem()->makeDirectory($destinationPath, 0775, true);
        }

        if ($this->getFilesystem()->copyDirectory($sourcePath, $destinationPath)) {
            if ($this->showMessage == true) {
                $this->console->line("<info>Published</info>: {$this->module->getStudlyName()}");
            }
        }
        else {
            $this->console->error($this->error);
        }
    }
}

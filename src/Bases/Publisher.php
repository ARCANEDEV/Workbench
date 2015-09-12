<?php namespace Arcanedev\Workbench\Bases;

use Arcanedev\Workbench\Contracts\PublisherInterface;
use Arcanedev\Workbench\Entities\Module;
use Arcanedev\Workbench\Workbench;
use Illuminate\Console\Command as IlluminateCommand;
use Illuminate\Filesystem\Filesystem;
use RuntimeException;

/**
 * Class     Publisher
 *
 * @package  Arcanedev\Workbench\Bases
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Publisher implements PublisherInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The module will used.
     *
     * @var Module
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
     * @var IlluminateCommand
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
     * @param  Module  $module
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
     * Get module instance.
     *
     * @return Module
     */
    public function getModule()
    {
        return $this->module;
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
     * Set modules repository instance.
     *
     * @param  Workbench  $workbench
     *
     * @return self
     */
    public function setWorkbench(Workbench $workbench)
    {
        $this->workbench = $workbench;

        return $this;
    }

    /**
     * Get console instance.
     *
     * @return IlluminateCommand
     */
    public function getConsole()
    {
        return $this->console;
    }

    /**
     * Set console instance.
     *
     * @param  IlluminateCommand  $console
     *
     * @return self
     */
    public function setConsole(IlluminateCommand $console)
    {
        if ($this->console instanceof IlluminateCommand) {
            $this->console = $console;

            return $this;
        }

        throw new RuntimeException(
            "The 'console' property must instance of \\Illuminate\\Console\\Command."
        );
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
     * Show the result message.
     *
     * @return self
     */
    public function showMessage()
    {
        return $this->setShowMessage(true);
    }

    /**
     * Hide the result message.
     *
     * @return self
     */
    public function hideMessage()
    {
        return $this->setShowMessage(false);
    }

    /**
     * Set the show message status
     *
     * @param  bool  $status
     *
     * @return self
     */
    private function setShowMessage($status)
    {
        $this->showMessage = $status;

        return $this;
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
        $sourcePath = $this->getSourcePath();

        if ( ! $this->isDirectory($sourcePath)) {
            return;
        }

        if ( ! $this->isDirectory($destinationPath = $this->getDestinationPath())) {
            $this->makeDirectory($destinationPath, 0775, true);
        }

        if (
            $this->copyDirectory($sourcePath, $destinationPath) &&
            $this->showMessage
        ) {
            $this->console->line("<info>Published</info>: {$this->module->getStudlyName()}");
        }
        else {
            $this->console->error($this->error);
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Determine if the given path is a directory.
     *
     * @param  string  $path
     *
     * @return bool
     */
    private function isDirectory($path)
    {
        return $this->getFilesystem()->isDirectory($path);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a directory.
     *
     * @param  string  $path
     * @param  int     $mode
     * @param  bool    $recursive
     *
     * @return bool
     */
    private function makeDirectory($path, $mode = 0755, $recursive = false)
    {
        return $this->getFilesystem()->makeDirectory($path, $mode, $recursive);
    }

    /**
     * Copy a directory from one location to another.
     *
     * @param  string  $directory
     * @param  string  $destination
     *
     * @return bool
     */
    public function copyDirectory($directory, $destination)
    {
        return $this->getFilesystem()->copyDirectory($directory, $destination);
    }
}

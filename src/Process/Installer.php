<?php namespace Arcanedev\Workbench\Process;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Arcanedev\Workbench\Workbench;
use Symfony\Component\Process\Process;

/**
 * Class Installer
 * @package Arcanedev\Workbench\Process
 */
class Installer
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The module name.
     *
     * @var string
     */
    protected $name;

    /**
     * The version of module being installed.
     *
     * @var string
     */
    protected $version;

    /**
     * The module repository instance.
     *
     * @var Workbench
     */
    protected $repository;

    /**
     * The console command instance.
     *
     * @var \Illuminate\Console\Command
     */
    protected $console;

    /**
     * The destination path.
     *
     * @var string
     */
    protected $path;

    /**
     * The process timeout.
     *
     * @var int
     */
    protected $timeout = 3360;

    /**
     * @var null|string
     */
    protected $type;

    /**
     * @var bool
     */
    protected $tree;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The constructor.
     *
     * @param string $name
     * @param string $version
     * @param string $type
     * @param bool   $tree
     */
    public function __construct($name, $version = null, $type = null, $tree = false)
    {
        $this->name    = $name;
        $this->version = $version;
        $this->type    = $type;
        $this->tree    = $tree;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set destination path.
     *
     * @param string $path
     *
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }
    /**
     * Set the module repository instance.
     *
     * @param  Workbench $repository
     *
     * @return self
     */
    public function setRepository(Workbench $repository)
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * Set console command instance.
     *
     * @param \Illuminate\Console\Command $console
     *
     * @return self
     */
    public function setConsole(Command $console)
    {
        $this->console = $console;

        return $this;
    }

    /**
     * Set process timeout.
     *
     * @param  int $timeout
     *
     * @return self
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * Get branch name.
     *
     * @return string
     */
    public function getBranch()
    {
        return is_null($this->version) ? 'master' : $this->version;
    }

    /**
     * Get module name.
     *
     * @return string
     */
    public function getModuleName()
    {
        $parts = explode('/', $this->name);

        return Str::studly(end($parts));
    }

    /**
     * Get composer package name.
     *
     * @return string
     */
    public function getPackageName()
    {
        if (is_null($this->version)) {
            return $this->name . ':dev-master';
        }

        return $this->name . ':' . $this->version;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Run the installation process.
     *
     * @return \Symfony\Component\Process\Process
     */
    public function run()
    {
        $process = $this->getProcess();
        $process->setTimeout($this->timeout);

        if ($this->console instanceof Command) {
            $process->run(function($type, $line) {
                $this->console->line($line);
            });
        }

        return $process;
    }

    /**
     * Get process instance.
     *
     * @return \Symfony\Component\Process\Process
     */
    public function getProcess()
    {
        switch ($this->type) {
            case 'github':
            case 'bitbucket':
                return $this->tree
                    ? $this->installViaSubtree()
                    : $this->installViaGit();
                // no break

            default:
                return $this->installViaComposer();
                // no break
        }
    }

    /**
     * Get destination path.
     *
     * @return string
     */
    public function getDestinationPath()
    {
        if ($this->path) {
            return $this->path;
        }

        return $this->repository->getModulePath($this->getModuleName());
    }

    /**
     * Get git repo url.
     *
     * @return string|null
     */
    public function getRepoUrl()
    {
        switch ($this->type) {
            case 'github':
                return "git@github.com:{$this->name}.git";
                // no break

            case 'bitbucket':
                return "git@bitbucket.org:{$this->name}.git";
                // no break

            default:
                return null;
                // no break
        }
    }

    /**
     * Install the module via git.
     *
     * @return \Symfony\Component\Process\Process
     */
    public function installViaGit()
    {
        return new Process(sprintf(
            'cd %s && git clone %s %s && git checkout %s',
            base_path(),
            $this->getRepoUrl(),
            $this->getDestinationPath(),
            $this->getBranch()
        ));
    }

    /**
     * Install the module via git subtree.
     *
     * @return \Symfony\Component\Process\Process
     */
    public function installViaSubtree()
    {
        return new Process(sprintf(
            'cd %s && git remote add %s %s && git subtree add --prefix=%s --squash %s %s',
            base_path(),
            $this->getModuleName(),
            $this->getRepoUrl(),
            $this->getDestinationPath(),
            $this->getModuleName(),
            $this->getBranch()
        ));
    }

    /**
     * Install the module via composer.
     *
     * @return \Symfony\Component\Process\Process
     */
    public function installViaComposer()
    {
        return new Process(sprintf(
            'cd %s && composer require %s',
            base_path(),
            $this->getPackageName()
        ));
    }
}

<?php namespace Arcanedev\Workbench\Generators;

use Arcanedev\Workbench\Exceptions\FileAlreadyExistException;
use Illuminate\Filesystem\Filesystem;

class FileGenerator
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The path wil be used.
     *
     * @var string
     */
    protected $path;

    /**
     * The contents will be used.
     *
     * @var string
     */
    protected $contents;

    /**
     * The laravel filesystem or null.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The constructor.
     *
     * @param string          $path
     * @param string          $contents
     * @param Filesystem|null $filesystem
     */
    public function __construct($path, $contents, $filesystem = null)
    {
        $this->path       = $path;
        $this->contents   = $contents;
        $this->filesystem = $filesystem ?: new Filesystem;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get contents.
     *
     * @return string
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * Set contents.
     *
     * @param  string $contents
     *
     * @return self
     */
    public function setContents($contents)
    {
        $this->contents = $contents;

        return $this;
    }

    /**
     * Get filesystem.
     *
     * @return Filesystem
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * Set filesystem.
     *
     * @param  Filesystem $filesystem
     *
     * @return self
     */
    public function setFilesystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;

        return $this;
    }

    /**
     * Get path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set path.
     *
     * @param  string $path
     *
     * @return self
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Generate the file.
     *
     * @throws FileAlreadyExistException
     *
     * @return int
     */
    public function generate()
    {
        if ( ! $this->filesystem->exists($path = $this->getPath())) {
            return $this->filesystem->put($path, $this->getContents());
        }

        throw new FileAlreadyExistException('File already exists!');
    }
}

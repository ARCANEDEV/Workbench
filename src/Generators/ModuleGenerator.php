<?php namespace Arcanedev\Workbench\Generators;

use Arcanedev\Support\Stub;
use Arcanedev\Workbench\Bases\Generator;
use Arcanedev\Workbench\Workbench;
use Illuminate\Config\Repository as Config;
use Illuminate\Console\Command as Console;
use Illuminate\Filesystem\Filesystem;

class ModuleGenerator extends Generator
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The module name will created.
     *
     * @var string
     */
    protected $name;

    /**
     * The laravel config instance.
     *
     * @var Config
     */
    protected $config;

    /**
     * The laravel filesystem instance.
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * The laravel console instance.
     *
     * @var Console
     */
    protected $console;

    /**
     * The workbench instance.
     *
     * @var Workbench
     */
    protected $workbench;

    /**
     * Force status.
     *
     * @var bool
     */
    protected $force = false;

    /**
     * Generate a plain module.
     *
     * @var bool
     */
    protected $plain = false;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The constructor.
     *
     * @param string     $name
     * @param Workbench  $workbench
     * @param Config     $config
     * @param Filesystem $filesystem
     * @param Console    $console
     */
    public function __construct(
        $name,
        Workbench $workbench   = null,
        Config $config         = null,
        Filesystem $filesystem = null,
        Console $console       = null
    ) {
        $this->setName($name);
        if ($workbench)  $this->setWorkbench($workbench);
        if ($config)     $this->setConfig($config);
        if ($filesystem) $this->setFilesystem($filesystem);
        if ($console)    $this->setConsole($console);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set plain flag.
     *
     * @param  bool $plain
     *
     * @return self
     */
    public function setPlain($plain)
    {
        $this->plain = $plain;

        return $this;
    }

    /**
     * Get the name of module will created.
     *
     * @return string
     */
    public function getName()
    {
        return str_slug($this->name);
    }

    /**
     * Set the name of module will created.
     *
     * @param  string $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the laravel config instance.
     *
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set the laravel config instance.
     *
     * @param  Config $config
     *
     * @return self
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Get the laravel filesystem instance.
     *
     * @return Filesystem
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * Set the laravel filesystem instance.
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
     * Get the laravel console instance.
     *
     * @return Console
     */
    public function getConsole()
    {
        return $this->console;
    }

    /**
     * Set the laravel console instance.
     *
     * @param  Console $console
     *
     * @return self
     */
    public function setConsole($console)
    {
        $this->console = $console;

        return $this;
    }

    /**
     * Get the Workbench instance.
     *
     * @return Workbench
     */
    public function getWorkbench()
    {
        return $this->workbench;
    }

    /**
     * Set the workbench instance.
     *
     * @param mixed $workbench
     *
     * @return $this
     */
    public function setWorkbench(Workbench $workbench)
    {
        $this->workbench = $workbench;

        return $this;
    }

    /**
     * Get the list of folders will created.
     *
     * @return array
     */
    public function getFolders()
    {
        return array_values($this->workbench->config('paths.generator'));
    }

    /**
     * Get the list of files will created.
     *
     * @return array
     */
    public function getFiles()
    {
        return $this->workbench->config('stubs.files');
    }

    /**
     * Set force status.
     *
     * @param  bool|int $force
     *
     * @return $this
     */
    public function setForce($force)
    {
        $this->force = (bool) $force;

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Generate the module.
     */
    public function generate()
    {
        $name = $this->getName();

        if ($this->workbench->has($name)) {
            if ($this->force) {
                $this->workbench->delete($name);
            }
            else {
                $this->console->error("Module [{$name}] already exist!");
                return;
            }
        }

        $this->generateFolders();
        $this->generateFiles();

        if ( ! $this->plain) {
            $this->generateResources();
        }

        $this->console->info("Module [{$name}] created successfully.");
    }

    /**
     * Generate the folders.
     */
    private function generateFolders()
    {
        foreach ($this->getFolders() as $folder) {
            $path = $this->workbench->getModulePath($this->getName()) . '/' . $folder;
            $this->filesystem->makeDirectory($path, 0755, true);
            $this->generateGitKeep($path);
        }
    }

    /**
     * Generate git keep to the specified path.
     *
     * @param string $path
     */
    private function generateGitKeep($path)
    {
        $this->filesystem->put($path . '/.gitkeep', '');
    }

    /**
     * Generate the files.
     */
    public function generateFiles()
    {
        foreach ($this->getFiles() as $stub => $file) {
            $path = $this->workbench->getModulePath($this->getName()) . $file;

            if ( ! $this->filesystem->isDirectory($dir = dirname($path))) {
                $this->filesystem->makeDirectory($dir, 0775, true);
            }

            $this->filesystem->put($path, $this->getStubContents($stub));
            $this->console->info("Created : {$path}");
        }
    }

    /**
     * Generate some resources.
     */
    public function generateResources()
    {
        $this->console->call('module:make-seed', [
            'name'     => $this->getName(),
            'module'   => $this->getName(),
            '--master' => true,
        ]);

        $this->console->call('module:make-provider', [
            'name'     => $this->getName() . 'ServiceProvider',
            'module'   => $this->getName(),
            '--master' => true,
        ]);

        $this->console->call('module:make-controller', [
            'controller' => $this->getName() . 'Controller',
            'module'     => $this->getName(),
        ]);
    }

    /**
     * Get the contents of the specified stub file by given stub name.
     *
     * @param  string $stub
     *
     * @return Stub
     */
    protected function getStubContents($stub)
    {
        return (new Stub(
            '/' . $stub . '.stub',
            $this->getReplacement($stub))
        )->render();
    }

    /**
     * get the list for the replacements.
     */
    public function getReplacements()
    {
        return $this->workbench->config('stubs.replacements');
    }

    /**
     * Get array replacement for the specified stub.
     *
     * @param  string $stub
     *
     * @return array
     */
    protected function getReplacement($stub)
    {
        $replacements = $this->workbench->config('stubs.replacements');

        if ( ! isset($replacements[$stub])) {
            return [];
        }

        $keys     = $replacements[$stub];

        $replaces = array_map(function ($key) {
            return method_exists($this, $method = $this->getReplacementMethod($key))
                ? call_user_func([$this, $method])
                : null;
        }, $keys);

        return array_filter(array_combine($keys, $replaces));
    }

    /* ------------------------------------------------------------------------------------------------
     |  Replacement Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get replacement method name
     *
     * @param  string $name
     *
     * @return string
     */
    private function getReplacementMethod($name)
    {
        return 'get' . ucfirst(str_studly(strtolower($name))) . 'Replacement';
    }

    /**
     * Get the module name in lower case.
     *
     * @return string
     */
    protected function getLowerNameReplacement()
    {
        return strtolower($this->getName());
    }

    /**
     * Get the module name in studly case.
     *
     * @return string
     */
    protected function getStudlyNameReplacement()
    {
        return str_studly($this->getName());
    }

    /**
     * Get replacement for $VENDOR$.
     *
     * @return string
     */
    protected function getVendorReplacement()
    {
        return $this->workbench->config('composer.vendor');
    }

    /**
     * Get replacement for $MODULE_NAMESPACE$.
     *
     * @return string
     */
    protected function getModuleNamespaceReplacement()
    {
        return str_replace('\\', '\\\\', $this->workbench->config('namespace'));
    }

    /**
     * Get replacement for $AUTHOR_NAME$.
     *
     * @return string
     */
    protected function getAuthorNameReplacement()
    {
        return $this->workbench->config('composer.author.name');
    }

    /**
     * Get replacement for $AUTHOR_EMAIL$.
     *
     * @return string
     */
    protected function getAuthorEmailReplacement()
    {
        return $this->workbench->config('composer.author.email');
    }
}

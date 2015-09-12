<?php namespace Arcanedev\Workbench;

use Arcanedev\Support\Collection;
use Arcanedev\Support\Json;
use Arcanedev\Workbench\Contracts\WorkbenchInterface;
use Arcanedev\Workbench\Entities\Module;
use Arcanedev\Workbench\Exceptions\ModuleNotFoundException;
use Arcanedev\Workbench\Process\Installer;
use Arcanedev\Workbench\Process\Updater;
use Countable;
use Illuminate\Foundation\Application;

/**
 * Class     Workbench
 *
 * @package  Arcanedev\Workbench
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Workbench implements WorkbenchInterface, Countable
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * The module path.
     *
     * @var string|null
     */
    protected $path;

    /**
     * The scanned paths.
     *
     * @var array
     */
    protected $paths = [];

    /**
     * @var string
     */
    protected $stubPath;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The constructor.
     *
     * @param Application $app
     * @param string|null $path
     */
    public function __construct(Application $app, $path = null)
    {
        $this->app  = $app;
        $this->path = $path;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Add other module location.
     *
     * @param  string $path
     *
     * @return self
     */
    public function addLocation($path)
    {
        $this->paths[] = $path;

        return $this;
    }

    /**
     * Alternative method for "addPath".
     *
     * @param string $path
     *
     * @return $this
     */
    public function addPath($path)
    {
        return $this->addLocation($path);
    }

    /**
     * Get all additional paths.
     *
     * @return array
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * Get a module path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path ?: $this->config('paths.modules');
    }

    /**
     * Get scanned modules paths.
     *
     * @return string[]
     */
    public function getScanPaths()
    {
        $paths      = $this->paths;
        $paths[]    = $this->getPath().'/*';

        if ($this->config('scan.enabled')) {
            $paths = array_merge($paths, $this->config('scan.paths'));
        }

        return $paths;
    }

    /**
     * Get stub path.
     *
     * @return string
     */
    public function getStubPath()
    {
        if ( ! is_null($this->stubPath)) {
            return $this->stubPath;
        }

        if ($this->config('stubs.enabled')) {
            return $this->config('stubs.path');
        }

        return $this->stubPath;
    }

    /**
     * Set stub path.
     *
     * @param  string $stubPath
     *
     * @return self
     */
    public function setStubPath($stubPath)
    {
        $this->stubPath = $stubPath;

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Boot the modules.
     */
    public function boot()
    {
        foreach ($this->getOrdered() as $module) {
            /** @var Module $module */
            $module->boot();
        }
    }

    /**
     * Register the modules.
     */
    public function register()
    {
        foreach ($this->getOrdered() as $module) {
            /** @var Module $module */
            $module->register();
        }
    }

    /**
     * Get & scan all modules.
     *
     * @return array
     */
    public function scan()
    {
        $paths      = $this->getScanPaths();
        $modules    = [];

        foreach ($paths as $key => $path) {
            $manifests = $this->app['files']->glob("{$path}/module.json");
            is_array($manifests) || $manifests = [];

            foreach ($manifests as $manifest) {
                $name           = Json::make($manifest)->get('name');
                $lowerName      = strtolower($name);
                $modules[$name] = new Module($this->app, $lowerName, dirname($manifest));
            }
        }

        return $modules;
    }

    /**
     * Get all modules.
     *
     * @return array
     */
    public function all()
    {
        if ( ! $this->config('cache.enabled')) {
            return $this->scan();
        }

        return $this->formatCached($this->getCached());
    }

    /**
     * Format the cached data as array of modules.
     *
     * @param  array $cached
     *
     * @return array
     */
    protected function formatCached($cached)
    {
        $modules = [];

        foreach ($cached as $name => $module) {
            $path      = $this->config('paths.modules') . '/' . $name;
            $modules[] = new Module($this->app, $name, $path);
        }

        return $modules;
    }

    /**
     * Get cached modules.
     *
     * @return array
     */
    public function getCached()
    {
        return $this->app['cache']->remember(
            $this->config('cache.key'),
            $this->config('cache.lifetime'),
            function () {
                return $this->toCollection()->toArray();
            }
        );
    }

    /**
     * Get all modules as collection instance.
     *
     * @return Collection
     */
    public function toCollection()
    {
        return new Collection($this->scan());
    }

    /**
     * Get modules by status.
     *
     * @param $status
     *
     * @return array
     */
    public function getByStatus($status)
    {
        $modules = [];

        foreach ($this->all() as $name => $module) {
            /** @var Module $module */
            if ($module->isStatus($status)) {
                $modules[$name] = $module;
            }
        }

        return $modules;
    }

    /**
     * Determine whether the given module exist.
     *
     * @param  string $name
     *
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, $this->all());
    }

    /**
     * Get list of enabled modules.
     *
     * @return array
     */
    public function enabled()
    {
        return $this->getByStatus(true);
    }

    /**
     * Get list of disabled modules.
     *
     * @return array
     */
    public function disabled()
    {
        return $this->getByStatus(false);
    }

    /**
     * Get count from all modules.
     *
     * @return int
     */
    public function count()
    {
        return count($this->all());
    }

    /**
     * Get all ordered modules.
     *
     * @param string $direction
     *
     * @return array
     */
    public function getOrdered($direction = 'asc')
    {
        $modules = $this->enabled();

        uasort($modules, function ($a, $b) use ($direction) {
            if ($a->order == $b->order) {
                return 0;
            }

            if ($direction == 'desc') {
                return $a->order < $b->order ? 1 : -1;
            }

            return $a->order > $b->order ? 1 : -1;
        });

        return $modules;
    }

    /**
     * Find a specific module.
     *
     * @param  string $name
     *
     * @return Module|null
     */
    public function find($name)
    {
        foreach ($this->all() as $module) {
            /** @var Module $module */
            if ($module->getLowerName() == strtolower($name)) {
                return $module;
            }
        }

        return null;
    }

    /**
     * Alternative for "find" method.
     *
     * @param  string $name
     *
     * @return Module|null
     */
    public function get($name)
    {
        return $this->find($name);
    }

    /**
     * Find a specific module, if there return that, otherwise throw exception.
     *
     * @param  string $name
     *
     * @throws ModuleNotFoundException
     *
     * @return Module
     */
    public function findOrFail($name)
    {
        if ( ! is_null($module = $this->find($name))) {
            return $module;
        }

        throw new ModuleNotFoundException("Module [{$name}] does not exist!");
    }

    /**
     * Get all modules as laravel collection instance.
     *
     * @return Collection
     */
    public function collections()
    {
        return new Collection($this->enabled());
    }

    /**
     * Get module path for a specific module.
     *
     * @param  string $module
     *
     * @return string
     */
    public function getModulePath($module)
    {
        try {
            return $this->findOrFail($module)->getPath() . '/';
        }
        catch (ModuleNotFoundException $e) {
            return $this->getPath() . '/' . str_slug($module) . '/';
        }
    }

    /**
     * Get asset path for a specific module.
     *
     * @param  string $module
     *
     * @return string
     */
    public function assetPath($module)
    {
        return $this->config('paths.assets') . '/' . $module;
    }

    /**
     * Get a specific config data from a configuration file.
     *
     * @param  string|null $key
     * @param  mixed       $default
     *
     * @return mixed
     */
    public function config($key = null, $default = null)
    {
        return config('workbench' . (is_null($key) ? '' : '.' . $key), $default);
    }

    /**
     * Get storage path for module used.
     *
     * @return string
     */
    public function getUsedStoragePath()
    {
        if ( ! $this->getFiles()->exists($path = storage_path('app/modules'))) {
            $this->getFiles()->makeDirectory($path, 0777, true);
        }

        return $path . '/modules.used';
    }

    /**
     * Get laravel filesystem instance.
     *
     * @return \Illuminate\Filesystem\Filesystem
     */
    public function getFiles()
    {
        return $this->app['files'];
    }

    /**
     * Get module used for cli session.
     *
     * @return Module
     */
    public function getUsedNow()
    {
        return $this->findOrFail(
            $this->getFiles()->get($this->getUsedStoragePath())
        );
    }

    /**
     * Set module used for cli session.
     *
     * @param  string $name
     *
     * @throws ModuleNotFoundException
     */
    public function setUsed($name)
    {
        $module = $this->findOrFail($name);
        $this->getFiles()->put($this->getUsedStoragePath(), $module);
    }

    /**
     * Get used now.
     *
     * @return Module
     */
    public function getUsed()
    {
        return $this->getUsedNow();
    }

    /**
     * Get module assets path.
     *
     * @return string
     */
    public function getAssetsPath()
    {
        return $this->config('paths.assets');
    }

    /**
     * Get asset url from a specific module.
     *
     * @param  string $asset
     *
     * @return string
     */
    public function asset($asset)
    {
        list($name, $url) = explode(':', $asset);
        $baseUrl          = str_replace(public_path(), '', $this->getAssetsPath());
        $url              = $this->app['url']->asset($baseUrl . "/{$name}/" . $url);

        return str_replace(['http://', 'https://'], '//', $url);
    }

    /**
     * Install the specified module.
     *
     * @param  string $name
     * @param  string $version
     * @param  string $type
     * @param  bool   $subtree
     *
     * @return \Symfony\Component\Process\Process
     */
    public function install($name, $version = 'dev-master', $type = 'composer', $subtree = false)
    {
        $installer = new Installer($name, $version, $type, $subtree);
        return $installer->run();
    }

    /**
     * Update dependencies for the specified module.
     *
     * @param string $module
     */
    public function update($module)
    {
        (new Updater($this))->update($module);
    }

    /**
     * Delete a specific module.
     *
     * @param  string $name
     *
     * @return bool
     */
    public function delete($name)
    {
        return $this->findOrFail($name)->delete();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Determine whether the given module is activated.
     *
     * @param  string $name
     *
     * @return bool
     */
    public function active($name)
    {
        return $this->findOrFail($name)->active();
    }

    /**
     * Determine whether the given module is not activated.
     *
     * @param  string $name
     *
     * @return bool
     */
    public function notActive($name)
    {
        return ! $this->active($name);
    }

    /**
     * Enabling a specific module.
     *
     * @param string $name
     *
     * @return self
     */
    public function enable($name)
    {
        $this->findOrFail($name)->enable();

        return $this;
    }

    /**
     * Disabling a specific module.
     *
     * @param  string $name
     *
     * @return self
     */
    public function disable($name)
    {
        $this->findOrFail($name)->disable();

        return $this;
    }
}

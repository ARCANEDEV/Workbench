<?php namespace Arcanedev\Workbench\Entities;

use Arcanedev\Support\Json;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcher;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

/**
 * Class Module
 * @package Arcanedev\Workbench\Entities
 *
 * @property string name
 * @property string alias
 * @property string description
 * @property array  keywords
 * @property int    active
 */
class Module extends ServiceProvider
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The laravel application instance.
     *
     * @var Application
     */

    protected $app;

    /**
     * The module name.
     *
     * @var string
     */
    protected $name;

    /**
     * The module path,.
     *
     * @var string
     */
    protected $path;

    /**
     * @var EventsDispatcher
     */
    private $events;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The constructor.
     *
     * @param Application $app
     * @param string      $name
     * @param string      $path
     */
    public function __construct(Application $app, $name, $path)
    {
        $this->app = $app;
        $this->setName($name);
        $this->setPath(realpath($path));
        $this->setDispatcher($app['events']);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get laravel instance.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function getLaravel()
    {
        return $this->app;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @param  string $name
     *
     * @return Module
     */
    private function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name in lower case.
     *
     * @return string
     */
    public function getLowerName()
    {
        return str_slug($this->name);
    }

    /**
     * Get name in studly case.
     *
     * @return string
     */
    public function getStudlyName()
    {
        return str_studly($this->name);
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

    /**
     * Get alias.
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->get('alias');
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->get('description');
    }

    /**
     * Get keywords
     *
     * @return array
     */
    public function getKeywords()
    {
        return $this->get('keywords', []);
    }

    /**
     * Get priority.
     *
     * @return string
     */
    public function getPriority()
    {
        return $this->get('priority');
    }

    /**
     * Set active state for current module.
     *
     * @param  bool $active
     *
     * @return bool
     */
    public function setActive($active)
    {
        $saved = $this->json()->set('active', (bool) $active)->save();

        return (bool) $saved;
    }

    /**
     * Get extra path.
     *
     * @param $path
     *
     * @return string
     */
    public function getExtraPath($path)
    {
        return $this->getPath() . '/' . $path;
    }

    /**
     * Get json contents.
     *
     * @return Json
     */
    public function json()
    {
        return new Json($this->getPath() . '/module.json', $this->app['files']);
    }

    /**
     * Handle call to __get method.
     *
     * @param  string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Get a specific data from json file by given the key.
     *
     * @param  string     $key
     * @param  mixed|null $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->json()->get($key, $default);
    }

    /**
     * Set event dispatcher
     *
     * @param  EventsDispatcher $events
     *
     * @return self
     */
    public function setDispatcher(EventsDispatcher $events)
    {
        $this->events = $events;

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->registerTranslation();

        $this->fireEvent('boot');
    }

    /**
     * Register module's translation.
     */
    protected function registerTranslation()
    {
        $lowerName  = $this->getLowerName();
        $langPath   = base_path("resources/lang/{$lowerName}");

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $lowerName);
        }
    }

    /**
     * Register the module.
     */
    public function register()
    {
        $this->registerAliases();
        $this->registerProviders();
        $this->registerFiles();
        $this->fireEvent('register');
    }

    /**
     * Register the aliases from this module.
     */
    private function registerAliases()
    {
        $loader = AliasLoader::getInstance();

        foreach ($this->get('aliases', []) as $aliasName => $aliasClass) {
            $loader->alias($aliasName, $aliasClass);
        }
    }

    /**
     * Register the service providers from this module.
     */
    private function registerProviders()
    {
        foreach ($this->get('providers', []) as $provider) {
            $this->app->register($provider);
        }
    }

    /**
     * Register the files from this module.
     */
    private function registerFiles()
    {
        foreach ($this->get('files', []) as $file) {
            include $this->path . '/' . $file;
        }
    }

    /**
     * Register the module event.
     *
     * @param string $event
     */
    private function fireEvent($event)
    {
        $eventName = sprintf('modules.%s.' . $event, $this->getLowerName());

        $this->events->fire($eventName, [$this]);
    }

    /**
     * Handle call __toString.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getStudlyName();
    }

    /**
     * Enable the current module.
     */
    public function enable()
    {
        $this->switchActive(true, 'enabling', 'enabled');
    }

    /**
     * Disable the current module.
     */
    public function disable()
    {
        $this->switchActive(false, 'disabling', 'disabled');
    }

    /**
     * Switch the current module active.
     *
     * @param bool   $value
     * @param string $before
     * @param string $after
     */
    private function switchActive($value, $before, $after)
    {
        $this->events->fire('module.' . $before, [$this]);
        $this->setActive($value);
        $this->events->fire('module.' . $after, [$this]);
    }

    /**
     * Delete the current module.
     *
     * @return bool
     */
    public function delete()
    {
        return $this->json()->getFilesystem()
            ->deleteDirectory($this->getPath(), true);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Determine whether the given status same with the current module status.
     *
     * @param  bool $status
     *
     * @return bool
     */
    public function isStatus($status)
    {
        return $this->get('active', false) == $status;
    }

    /**
     * Determine whether the current module activated.
     *
     * @return bool
     */
    public function enabled()
    {
        return $this->active();
    }

    /**
     * Alternate for "enabled" method.
     *
     * @return bool
     */
    public function active()
    {
        return $this->isStatus(true);
    }

    /**
     * Determine whether the current module not activated.
     *
     * @return bool
     */
    public function notActive()
    {
        return ! $this->active();
    }

    /**
     * Alias for "notActive" method.
     *
     * @return bool
     */
    public function disabled()
    {
        return ! $this->enabled();
    }
}

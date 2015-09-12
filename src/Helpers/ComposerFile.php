<?php namespace Arcanedev\Workbench\Helpers;

use Arcanedev\Support\Json;

/**
 * Class     ComposerFile
 *
 * @package  Arcanedev\Workbench\Helpers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ComposerFile
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const FILE_NAME = 'composer.json';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @var string
     */
    protected $path = '';

    /**
     * @var Json
     */
    protected $json;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Constructor.
     *
     * @param string|null $path
     */
    public function __construct($path = null)
    {
        if (is_null($path)) {
            $path = base_path(self::FILE_NAME);
        }

        $this->setPath($path);
        $this->load();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set path.
     *
     * @param  string  $path
     *
     * @return $this
     */
    private function setPath($path)
    {
        $this->path = realpath($path);

        return $this;
    }

    /**
     * Get extra attribute.
     *
     * @param  array|null  $default
     *
     * @return mixed
     */
    public function getExtra($default = [])
    {
        return $this->json->get('extra', $default);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Load the composer.json file.
     */
    private function load()
    {
        $this->json = new Json($this->path);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if has extra attribute.
     *
     * @return bool
     */
    public function hasExtra()
    {
        return $this->has('extra');
    }

    /**
     * Check if has merge module feature.
     *
     * @return bool
     */
    public function hasMergeModules()
    {
        if ( ! $this->has('extra')) {
            return false;
        }

        return isset($this->getExtra()['merge-plugin']);
    }

    /**
     * Check if has an attribute.
     *
     * @param  string  $key
     *
     * @return bool
     */
    protected function has($key)
    {
        return ! is_null($this->json->get($key, null));
    }

    /**
     * Add merge modules.
     *
     * @return self
     */
    public function addMergeModules()
    {
        $extra = array_merge($this->getExtra(), [
            'merge-plugin' => [
                "include"   => ["modules/*/composer.json"],
            ],
        ]);

        $this->json->set('extra', $extra)->save();

        return $this;
    }
}

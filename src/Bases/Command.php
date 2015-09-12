<?php namespace Arcanedev\Workbench\Bases;

use Arcanedev\Support\Bases\Command as BaseCommand;

/**
 * Class     Command
 *
 * @package  Arcanedev\Workbench\Bases
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Command extends BaseCommand
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the string value of a command argument.
     *
     * @param  string  $key
     *
     * @return string
     */
    public function getStringArg($key)
    {
        return (string) $this->argument($key);
    }

    /**
     * Get the string value of a command option.
     *
     * @param  string  $key
     *
     * @return string
     */
    public function getStringOption($key)
    {
        return (string) $this->option($key);
    }

    /**
     * Get the boolean value of a command option.
     *
     * @param  string  $key
     *
     * @return bool
     */
    public function getBoolOption($key)
    {
        return (bool) $this->option($key);
    }
}

<?php namespace Arcanedev\Workbench\Bases;

use Illuminate\Console\Command as IlluminateCommand;

/**
 * Class Command
 * @package Arcanedev\Workbench\Bases
 */
abstract class Command extends IlluminateCommand
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

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

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Execute the console command.
     */
    abstract public function handle();
}

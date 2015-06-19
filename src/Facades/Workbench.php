<?php namespace Arcanedev\Workbench\Facades;

use Illuminate\Support\Facades\Facade;

class Workbench extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'workbench'; }
}

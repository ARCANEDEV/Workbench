<?php namespace Arcanedev\Workbench\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class     Workbench
 *
 * @package  Arcanedev\Workbench\Facades
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Workbench extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'arcanedev.workbench'; }
}

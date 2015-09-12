<?php

if ( ! function_exists('workbench')) {
    /**
     * Get workbench instance.
     *
     * @return \Arcanedev\Workbench\Workbench
     */
    function workbench()
    {
        return app('arcanedev.workbench');
    }
}

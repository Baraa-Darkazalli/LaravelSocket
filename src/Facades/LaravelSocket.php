<?php

namespace BaraaDark\LaravelSocket\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelSocket extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'LaravelSocket';
    }
}

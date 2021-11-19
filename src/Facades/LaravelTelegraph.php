<?php

namespace DefStudio\LaravelTelegraph\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \DefStudio\LaravelTelegraph\LaravelTelegraph
 */
class LaravelTelegraph extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-telegraph';
    }
}

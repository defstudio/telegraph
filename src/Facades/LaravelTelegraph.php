<?php

namespace DefStudio\LaravelTelegraph\Facades;

use DefStudio\LaravelTelegraph\Support\Testing\Fakes\LaravelTelegraphFake;
use Illuminate\Support\Facades\Facade;

/**
 * @see \DefStudio\LaravelTelegraph\LaravelTelegraph
 */
class LaravelTelegraph extends Facade
{
    public static function fake(): LaravelTelegraphFake
    {
        static::swap($fake = new LaravelTelegraphFake());

        return $fake;
    }

    protected static function getFacadeAccessor(): string
    {
        return 'laravel-telegraph';
    }
}

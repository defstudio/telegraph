<?php

namespace DefStudio\Telegraph\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string|false callbackClassByName(string $botName, string $name)
 *
 * @see \DefStudio\Telegraph\CallbackResolver
 */
class CallbackResolver extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'callbackResolver';
    }
}

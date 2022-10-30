<?php

namespace DefStudio\Telegraph\Facades;

use DefStudio\Telegraph\DTO\CallbackData;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string callbackClassByName(string $botName, string $name)
 * @method static CallbackData toCallbackData(string $botName, string $rawData)
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

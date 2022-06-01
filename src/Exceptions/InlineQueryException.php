<?php

namespace DefStudio\Telegraph\Exceptions;

class InlineQueryException extends \Exception
{
    public static function invalidSwitchToPmParameter(string $parameter): InlineQueryException
    {
        return new InlineQueryException("Parameter [$parameter] for 'switch to private message' of InlineQueryAnswer is invalid. Only [A-Z, a-z, 0-9, _ and -] allowed");
    }
}

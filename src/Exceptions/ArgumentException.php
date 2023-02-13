<?php

namespace DefStudio\Telegraph\Exceptions;

use Exception;

final class ArgumentException extends Exception
{
    public static function missing(string $parameter): ArgumentException
    {
        return new self("Missing parameter: $parameter");
    }
}

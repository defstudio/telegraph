<?php

namespace DefStudio\Telegraph\Exceptions;

use Exception;

final class KeyboardException extends Exception
{
    public static function undefinedMethod(string $name): KeyboardException
    {
        return new self("Undefined keyboard method [$name]");
    }
}

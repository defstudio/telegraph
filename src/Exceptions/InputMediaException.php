<?php

namespace DefStudio\Telegraph\Exceptions;

class InputMediaException extends \Exception
{
    public static function undefinedFormat(): self
    {
        return new self("Undefined format media path.");
    }
}

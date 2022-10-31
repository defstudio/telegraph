<?php

namespace DefStudio\Telegraph\Exceptions;

class InputMediaException extends \Exception
{
    public static function undefinedFormat(string $path): self
    {
        return new self("Undefined format media path: " . $path);
    }
}

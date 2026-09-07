<?php

namespace DefStudio\Telegraph\Exceptions;

use Exception;

final class RichBlockFactoryException extends Exception
{
    public static function missingType(): RichBlockFactoryException
    {
        return new self("Rich Block data missing type");
    }

    public static function invalidType(string $type): RichBlockFactoryException
    {
        return new self(sprintf("Invalid Rich Block Type: `%s`", $type));
    }
}

<?php

namespace DefStudio\Telegraph\Exceptions;

use Exception;

final class RichTextFactoryException extends Exception
{
    public static function invalidType(string $type): RichTextFactoryException
    {
        return new self(sprintf("Invalid Factory Rich Text Item Type: `%s`", $type));
    }

    public static function structureMismatch(): RichTextFactoryException
    {
        return new self("The Factory Rich Text provided structure is not valid");
    }
}

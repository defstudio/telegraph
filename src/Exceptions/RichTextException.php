<?php

namespace DefStudio\Telegraph\Exceptions;

use Exception;

final class RichTextException extends Exception
{
    public static function structureMismatch(): RichTextException
    {
        return new self("The RichTextItem provided structure is not valid");
    }
}

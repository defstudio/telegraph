<?php

namespace DefStudio\Telegraph\Exceptions;

use Exception;

final class RichBlockException extends Exception
{
    public static function structureMismatch(): RichBlockException
    {
        return new self("The RichBlockItem provided structure is not valid");
    }
}

<?php

namespace DefStudio\Telegraph\Exceptions;

use Exception;

/**
 * @internal
 */
final class TelegraphException extends Exception
{
    public static function missingBot(): TelegraphException
    {
        return new self("No TelegraphBot defined for this request");
    }

    public static function missingChat(): TelegraphException
    {
        return new self("No TelegraphChat defined for this request");
    }

    public static function noActionDefined(): TelegraphException
    {
        return new self("Trying to send a request without setting an endpoint");
    }
}

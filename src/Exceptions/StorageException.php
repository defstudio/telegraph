<?php

namespace DefStudio\Telegraph\Exceptions;

use Exception;

class StorageException extends Exception
{
    public static function noDefaultDriver(): StorageException
    {
        return new self("No default driver defined in telegraph.storage.default config");
    }

    public static function driverNotFound(string $driver): StorageException
    {
        return new self("No [$driver] driver configuration defined telegraph.storage.stores.$driver config");
    }
}

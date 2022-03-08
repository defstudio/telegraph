<?php

namespace DefStudio\Telegraph\Exceptions;

use Exception;

final class BotCommandException extends Exception
{
    public static function tooManyCommands(): BotCommandException
    {
        return new self("Too many commands for telegram bot. Max 100 commands allowed.");
    }

    public static function invalidCommand(string $command): BotCommandException
    {
        return new self("Invalid command [$command]. Only English lowercase letters, digits and underscore allowed.");
    }

    public static function longCommand(string $command): BotCommandException
    {
        return new self("Invalid command [$command]. Max 32 characters allowed.");
    }

    public static function longDescription(string $command): BotCommandException
    {
        return new self("Invalid description for command [$command]. Max 256 characters allowed.");
    }
}

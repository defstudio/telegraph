<?php

namespace DefStudio\Telegraph\Exceptions;

use Exception;

/**
 * @internal
 */
final class TelegramException extends Exception
{
    public static function missingBotToken(): TelegramException
    {
        return new self("Missing Telegram Bot Token");
    }

    public static function missingChatId(): TelegramException
    {
        return new self("Missing Telegram destination Chat ID");
    }

    public static function noActionDefined(): TelegramException
    {
        return new self("Trying to send a request without setting an endpoint");
    }
}

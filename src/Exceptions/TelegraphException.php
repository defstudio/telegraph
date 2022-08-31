<?php

namespace DefStudio\Telegraph\Exceptions;

use Exception;

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

    public static function noEndpoint(): TelegraphException
    {
        return new self("Trying to send a request without setting an endpoint");
    }

    public static function failedToRetrieveBotInfo(): TelegraphException
    {
        return new self("Failed to retrieve bot info from telegram");
    }

    public static function failedToRetrieveChatInfo(): TelegraphException
    {
        return new self("Failed to retrieve chat info from telegram");
    }

    public static function invalidChatAction(string $action): TelegraphException
    {
        return new self("Invalid chat action: $action");
    }
}

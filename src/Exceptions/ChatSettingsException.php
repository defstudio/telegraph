<?php

namespace DefStudio\Telegraph\Exceptions;

use Exception;

final class ChatSettingsException extends Exception
{
    public static function titleMaxLengthExceeded(): ChatSettingsException
    {
        return new self("Telegram Chat title max length (255) exceeded");
    }

    public static function emptyTitle(): ChatSettingsException
    {
        return new self("Telegram Chat title cannot be empty");
    }

    public static function descriptionMaxLengthExceeded(): ChatSettingsException
    {
        return new self("Telegram Chat description max length (255) exceeded");
    }
}

<?php

namespace DefStudio\Telegraph\Exceptions;

class TelegramWebhookException extends \Exception
{
    public static function invalidAction(string $action): TelegramWebhookException
    {
        return new self("No Telegram Webhook handler defined for received action: $action");
    }

    public static function invalidCommand(string $command): TelegramWebhookException
    {
        return new self("No Telegram Webhook handler defined for received $command: $command");
    }
}

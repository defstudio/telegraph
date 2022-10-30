<?php

namespace DefStudio\Telegraph\Exceptions;

final class TelegramWebhookException extends \Exception
{
    public static function invalidAction(string $action): TelegramWebhookException
    {
        return new self("No Telegram Webhook handler defined for received action: $action");
    }

    public static function invalidCommand(string $command): TelegramWebhookException
    {
        return new self("No Telegram Webhook handler defined for received $command: $command");
    }

    public static function invalidData(string $description): TelegramWebhookException
    {
        return new self("Invalid data: $description");
    }

    public static function invalidScheme(): TelegramWebhookException
    {
        return new self("You application must have a secure (https) url in order to accept webhook calls");
    }
}

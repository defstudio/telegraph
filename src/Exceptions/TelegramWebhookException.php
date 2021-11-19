<?php

namespace DefStudio\LaravelTelegraph\Exceptions;

class TelegramWebhookException extends \Exception
{
    public static function invalidAction(string $action): TelegramWebhookException
    {
        return new self("No Telegram Webhook handler defined for received action: $action");
    }
}

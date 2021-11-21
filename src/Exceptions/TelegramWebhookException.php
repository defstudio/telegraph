<?php

namespace DefStudio\Telegraph\Exceptions;

use DefStudio\Telegraph\Handlers\WebhookHandler;

class TelegramWebhookException extends \Exception
{
    public static function invalidAction(string $action): TelegramWebhookException
    {
        return new self("No Telegram Webhook handler defined for received action: $action");
    }

    public static function invalidActionName(string $action): TelegramWebhookException
    {
        return new self(sprintf("Cannot use [%s] as action name, it is a reserved method from %s class", $action, WebhookHandler::class));
    }
}

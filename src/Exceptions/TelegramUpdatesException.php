<?php

namespace DefStudio\Telegraph\Exceptions;

use DefStudio\Telegraph\Models\TelegraphBot;

class TelegramUpdatesException extends \Exception
{
    public static function webhookExist(TelegraphBot $bot): TelegramUpdatesException
    {
        return new self("Cannot retrieve updates for $bot->name bot while a webhook is set. First, delete the webhook with [artisan telegraph:delete-webhook $bot->id] or programmatically calling [\$bot->deleteWebhook()]");
    }

    public static function pollingError(TelegraphBot $bot, string $errorMessage): TelegramUpdatesException
    {
        return new self("Cannot retrieve updates for $bot->name bot: $errorMessage");
    }
}

<?php

namespace DefStudio\LaravelTelegraph\Commands;

use DefStudio\LaravelTelegraph\Facades\LaravelTelegraph;
use Illuminate\Console\Command;


class SetTelegramWebhookCommand extends Command
{
    public $signature = 'telegraph:set-webhook';

    public $description = 'Set webhook url in telegram bot configuration';

    public function handle(): int
    {
       LaravelTelegraph::registerWebhook()->send();

        //TODO: handle failure

        return self::SUCCESS;
    }
}

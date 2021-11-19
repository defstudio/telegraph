<?php

namespace DefStudio\LaravelTelegraph\Commands;

use Illuminate\Console\Command;
use LaravelTelegraph;

class SetTelegramWebhookCommand extends Command
{
    public $signature = 'telegraph:set-webook';

    public $description = 'Set webhook url in telegram bot configuration';

    public function handle(): int
    {
       LaravelTelegraph::registerWebhook()->send();

        //TODO: handle failure

        return self::SUCCESS;
    }
}

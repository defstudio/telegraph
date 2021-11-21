<?php

namespace DefStudio\Telegraph\Commands;

use DefStudio\Telegraph\Facades\Telegraph;
use Illuminate\Console\Command;

class SetTelegramWebhookCommand extends Command
{
    public $signature = 'telegraph:set-webhook';

    public $description = 'Set webhook url in telegram bot configuration';

    public function handle(): int
    {
        $telegraph = Telegraph::registerWebhook();

        $this->info("Sending webhook setup request to: {$telegraph->getUrl()}");

        $reponse = $telegraph->send();

        if (!$reponse->json('ok')) {
            $this->error("Failed to register webhook");
            dump($reponse->json());

            return self::FAILURE;
        }

        $this->info('Webhook updated');

        return self::SUCCESS;
    }
}

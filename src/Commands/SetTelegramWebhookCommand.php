<?php

namespace DefStudio\Telegraph\Commands;

use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Console\Command;

class SetTelegramWebhookCommand extends Command
{
    public $signature = 'telegraph:set-webhook
                            {bot? : the ID of the bot (if the system contain a single bot, it can be left empty)}';

    public $description = 'Set webhook url in telegram bot configuration';

    public function handle(): int
    {
        $bot = rescue(fn () => TelegraphBot::fromId($this->argument('bot')));

        if (empty($bot)) {
            $this->error("Please specify a Bot ID");

            return self::FAILURE;
        }

        $telegraph = $bot->registerWebhook();

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

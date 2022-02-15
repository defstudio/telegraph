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
        /** @var int|null $bot_id */
        $bot_id = $this->argument('bot');

        /** @var TelegraphBot|null $bot */
        $bot = rescue(fn () => TelegraphBot::fromId($bot_id), report: false);

        if (empty($bot)) {
            $this->error("Please specify a Bot ID");

            return self::FAILURE;
        }

        $telegraph = $bot->registerWebhook();

        $this->info("Sending webhook setup request to: {$telegraph->getUrl()}");

        $reponse = $telegraph->send();

        /** @var bool $ok */
        $ok = (bool)$reponse->json('ok');

        if (!$ok) {
            $this->error("Failed to register webhook");
            $this->error($reponse->body());

            return self::FAILURE;
        }

        $this->info('Webhook updated');

        return self::SUCCESS;
    }
}

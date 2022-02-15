<?php

namespace DefStudio\Telegraph\Commands;

use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Console\Command;

class GetTelegramWebhookDebugInfoCommand extends Command
{
    public $signature = 'telegraph:debug-webhook
                            {bot? : the ID of the bot (if the system contain a single bot, it can be left empty)}';

    public $description = 'get webhook debug infro from telegram bot';

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

        $response = $bot->getWebhookDebugInfo()->send();

        if (!$response->json('ok')) {
            $this->error("Failed to get log from telegram server");
            $this->error($response->body());

            return self::FAILURE;
        }

        /** @var array<string, string|int|bool> $result */
        $result = $response->json('result');

        foreach ($result as $key => $value) {
            if (is_bool($value)) {
                $value = $value ? 'yes' : 'no';
            }

            $this->line("$key: $value");
        }

        return self::SUCCESS;
    }
}

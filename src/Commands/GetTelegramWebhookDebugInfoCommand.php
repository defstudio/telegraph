<?php

namespace DefStudio\Telegraph\Commands;

use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Console\Command;

class GetTelegramWebhookDebugInfoCommand extends Command
{
    public $signature = 'telegraph:debug-webhook
                            {bot? : the ID of the bot (if the system contain a single bot, it can be left empty)}';

    public $description = 'Get webhook debug info from telegram bot';

    public function handle(): int
    {
        /** @var int|null $bot_id */
        $bot_id = $this->argument('bot');

        /** @var class-string<TelegraphBot> $botModel */
        $botModel = config('telegraph.models.bot');

        /** @var TelegraphBot|null $bot */
        $bot = rescue(fn () => $botModel::fromId($bot_id), report: false);

        if (empty($bot)) {
            $this->error(__('telegraph::errors.missing_bot_id'));

            return self::FAILURE;
        }

        $response = $bot->getWebhookDebugInfo()->send();

        if (!$response->json('ok')) {
            $this->error(__('telegraph::errors.failed_to_get_log_from_telegram'));
            $this->error($response->body());

            return self::FAILURE;
        }

        /** @var array<string, string|int|bool> $result */
        $result = $response->json('result');

        foreach ($result as $key => $value) {
            if (is_bool($value)) {
                $value = $value ? __('telegraph::misc.yes') : __('telegraph::misc.no');
            }

            /** @phpstan-ignore-next-line  */
            $this->line("$key: $value");
        }

        return self::SUCCESS;
    }
}

<?php

namespace DefStudio\Telegraph\Commands;

use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Console\Command;

class SetTelegramWebhookCommand extends Command
{
    public $signature = 'telegraph:set-webhook
                            {bot? : the ID of the bot (if the system contain a single bot, it can be left empty)}
                            {--drop-pending-updates : drops pending updates from telegram}
                            {--max-connections=40 : maximum allowed simultaneous connections to the webhook (defaults to 40)}
                            {--secret= : secret token to be sent in a X-Telegram-Bot-Api-Secret-Token header to verify the authenticity of the webhook}
                         ';

    public $description = 'Set webhook url in telegram bot configuration';

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

        $dropPendingUpdates = $this->option('drop-pending-updates');
        $maxConnections = $this->option('max-connections');
        $secret = $this->option('secret');

        /* @phpstan-ignore-next-line  */
        $telegraph = $bot->registerWebhook($dropPendingUpdates, $maxConnections, $secret);

        $this->info(__('telegraph::commands.set_webhook.sending_setup_request', ['api_url' => $telegraph->getApiUrl()]));

        $reponse = $telegraph->send();

        $ok = (bool) $reponse->json('ok');

        if (!$ok) {
            $this->error(__('telegraph::errors.failed_to_register_webhook'));
            $this->error($reponse->body());

            return self::FAILURE;
        }

        $this->info(__('telegraph::commands.set_webhook.webhook_updated'));

        return self::SUCCESS;
    }
}

<?php

namespace DefStudio\Telegraph\Commands;

use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Console\Command;

class UnsetTelegramWebhookCommand extends Command
{
    public $signature = 'telegraph:unset-webhook
                            {bot? : the ID of the bot (if the system contain a single bot, it can be left empty)}
                            {--drop-pending-updates: if set, upon webhook deletion, all pending updates will be discarded}';

    public $description = 'Unregister the webhook in telegram bot configuration';

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

        $telegraph = $bot->unregisterWebhook($this->hasOption('drop-pending-updates'));

        $this->info(__('telegraph::commands.unset_webhook.sending_unset_request', ['api_url' => $telegraph->getApiUrl()]));

        $reponse = $telegraph->send();

        $ok = (bool)$reponse->json('ok');

        if (!$ok) {
            $this->error(__('telegraph::errors.failed_to_unregister_webhook'));
            $this->error($reponse->body());

            return self::FAILURE;
        }

        $this->info(__('telegraph::commands.unset_webhook.webhook_deleted'));

        return self::SUCCESS;
    }
}

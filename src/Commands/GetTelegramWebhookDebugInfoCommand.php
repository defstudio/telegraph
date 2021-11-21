<?php

namespace DefStudio\Telegraph\Commands;

use DefStudio\Telegraph\Facades\Telegraph;
use Illuminate\Console\Command;

class GetTelegramWebhookDebugInfoCommand extends Command
{
    public $signature = 'telegraph:debug-webhook';

    public $description = 'get webhook debug infro from telegram bot';

    public function handle(): int
    {
        $reponse = Telegraph::getWebhookDebugInfo()->send();

        if (!$reponse->json('ok')) {
            $this->error("Failed to get log from telegram server");
            dump($reponse->json());

            return self::FAILURE;
        }

        /** @var array<string, string|int|bool> $result */
        $result = $reponse->json('result');

        foreach ($result as $key => $value) {
            if (is_bool($value)) {
                $value = $value ? 'yes' : 'no';
            }

            $this->line("$key: $value");
        }

        //TODO: handle failure

        return self::SUCCESS;
    }
}

<?php

namespace DefStudio\LaravelTelegraph\Commands;

use DefStudio\LaravelTelegraph\Facades\LaravelTelegraph;
use Illuminate\Console\Command;
use Illuminate\Http\Client\Response;

class GetTelegramWebhookDebugInfoCommand extends Command
{
    public $signature = 'telegraph:debug-webhook';

    public $description = 'get webhook debug infro from telegram bot';

    public function handle(): int
    {
        /** @var Response $reponse */
        $reponse = LaravelTelegraph::getWebhookDebugInfo()->send();

        dump($reponse->json());

        //TODO: handle failure

        return self::SUCCESS;
    }
}

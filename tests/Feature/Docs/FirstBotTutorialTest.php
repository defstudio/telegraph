<?php

use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Handlers\WebhookHandler;

class FirstBotTutorialWebhookHandler extends WebhookHandler
{
    public function start(): void
    {
        $this->chat
            ->html('Hello! Telegraph is connected.')
            ->send();
    }
}

it('обрабатывает команду /start из tutorial', function () {
    $bot = bot();

    Telegraph::fake();

    app(FirstBotTutorialWebhookHandler::class)->handle(
        webhook_command('/start'),
        $bot,
    );

    Telegraph::assertSent('Hello! Telegraph is connected.');
});

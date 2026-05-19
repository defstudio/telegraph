<?php

use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Telegraph as TelegraphCore;
use Illuminate\Http\Request;

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
    $bot = TelegraphBot::create([
        'token' => 'test-bot-token',
        'name' => 'Support Bot',
    ]);

    $chat = $bot->chats()->create([
        'chat_id' => '-123456789',
        'name' => 'Personal chat',
    ]);

    Telegraph::fake();

    app(FirstBotTutorialWebhookHandler::class)->handle(
        Request::create('', 'POST', [
            'message' => [
                'message_id' => 123456,
                'chat' => [
                    'id' => (int) $chat->chat_id,
                    'type' => 'private',
                    'username' => 'john-smith',
                ],
                'text' => '/start',
                'date' => 1646516736,
            ],
        ]),
        $bot,
    );

    Telegraph::assertSent('Hello! Telegraph is connected.');
    Telegraph::assertSentData(TelegraphCore::ENDPOINT_MESSAGE, [
        'chat_id' => $chat->chat_id,
        'text' => 'Hello! Telegraph is connected.',
    ], exact: false);
});

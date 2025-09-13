<?php

/** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection PhpUnusedPrivateMethodInspection */

namespace DefStudio\Telegraph\Tests\Support;

use DefStudio\Telegraph\DTO\ChatJoinRequest;
use DefStudio\Telegraph\DTO\ChatMemberUpdate;
use DefStudio\Telegraph\DTO\InlineQuery;
use DefStudio\Telegraph\DTO\InlineQueryResultGif;
use DefStudio\Telegraph\DTO\Poll;
use DefStudio\Telegraph\DTO\PollAnswer;
use DefStudio\Telegraph\DTO\SuccessfulPayment;
use DefStudio\Telegraph\DTO\User;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Stringable;

class TestWebhookHandler extends WebhookHandler
{
    public static bool $handleUnknownCommands = false;

    public static int $calls_count = 0;
    public static array $extracted_data = [];

    public static function reset()
    {
        self::$calls_count = 0;
        self::$extracted_data = [];
    }

    public function test(): void
    {
        self::$calls_count++;
    }

    public function trigger_failure()
    {
        throw new Exception('foo');
    }

    public function send_reply(): void
    {
        $this->reply('foo');
    }

    public function change_keyboard(): void
    {
        $this->replaceKeyboard(
            Keyboard::make()
                ->row([
                    Button::make('test')->action('test')->param('id', 1),
                ])
                ->row([
                    Button::make('delete')->action('delete')->param('id', 2),
                ])
        );
    }

    public function delete_keyboard(): void
    {
        $this->deleteKeyboard();
    }

    public function hello($parameter = null): void
    {
        if ($parameter) {
            $this->chat->html("Hello!! your parameter is [$parameter]")->send();
        }
        $this->chat->html("Hello!!")->send();
    }

    public function param_injection(string $foo = 'not set'): void
    {
        $this->chat->html("Foo is [$foo]")->send();
    }

    public function reply_to_command(): void
    {
        $this->reply('foo');
    }

    private function private_action(): void
    {
    }

    protected function extractCallbackQueryData(): void
    {
        parent::extractCallbackQueryData();

        self::$extracted_data = [
            'chatId' => $this->chat->id,
            'messageId' => $this->messageId,
            'callbackQueryId' => $this->callbackQueryId,
            'originalKeyboard' => $this->originalKeyboard,
            'data' => $this->data,
        ];
    }

    protected function handleInlineQuery(InlineQuery $inlineQuery): void
    {
        $this->bot->answerInlineQuery($inlineQuery->id(), [
            InlineQueryResultGif::make(99, 'https://gif.dev', 'https://thumb.gif.test')
                ->caption('foo')
                ->title('bar')
                ->duration(200)
                ->height(400)
                ->width(300)
                ->keyboard(Keyboard::make()->button('buy')->action('buy')->param('id', 99)),
            InlineQueryResultGif::make(98, 'https://gif2.dev', 'https://thumb.gif2.test')
                ->caption('baz')
                ->title('quz')
                ->duration(1200)
                ->height(1400)
                ->width(1300)
                ->keyboard(Keyboard::make()->button('buy')->action('buy')->param('id', 98)),

        ])->send();
    }

    protected function handleUnknownCommand(Stringable $text): void
    {
        if (!self::$handleUnknownCommands) {
            parent::handleUnknownCommand($text);
        }

        $this->chat->html("I can't understand your command: $text")->send();
    }

    protected function handleChatMessage(Stringable $text): void
    {
        $this->chat->html("Received: $text")->send();
    }

    protected function handleChatMemberJoined(User $member): void
    {
        $this->chat->html("Welcome {$member->firstName()}")->send();
    }

    protected function handleChatMemberLeft(User $member): void
    {
        $this->chat->html("{$member->firstName()} just left")->send();
    }

    protected function handleChatJoinRequest(ChatJoinRequest $chatJoinRequest): void
    {
        $this->chat->approveJoinRequest($chatJoinRequest->userChatId())->send();
    }

    protected function handleChatReaction(Collection $newReactions, Collection $oldReactions): void
    {
        $this->chat->html(implode(':', [
            /* @phpstan-ignore-next-line */
            'New reaction is ' . $newReactions->first()->emoji(),
            /* @phpstan-ignore-next-line */
            'Old reaction is ' . $oldReactions->first()->emoji(),
        ]))->send();
    }

    protected function handleSuccessfulPayment(SuccessfulPayment $successfulPayment): void
    {
        $this->bot->chats->first()->html('payment')->send();
    }

    protected function handleChatMemberUpdate(ChatMemberUpdate $chatMemberUpdate): void
    {
        $this->bot->chats->first()->html('updated')->send();
    }

    protected function handleBotChatStatusUpdate(ChatMemberUpdate $chatMemberUpdate): void
    {
        $this->bot->chats->first()->html('banned')->send();
    }

    protected function handleMigrateToChat(): void
    {
        parent::handleMigrateToChat();

        $this->chat->html('We are a Supergroup now')->send();
    }

    protected function handlePollStateUpdate(Poll $poll): void
    {
        $this->bot->chats->first()->html('Poll state updated')->send();
    }

    protected function handlePollAnswer(PollAnswer $pollAnswer): void
    {
        $this->bot->chats->first()->html('Poll answer received')->send();
    }
}

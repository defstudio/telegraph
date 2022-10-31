<?php

namespace DefStudio\Telegraph;

use DefStudio\Telegraph\DTO\CallbackQuery;
use DefStudio\Telegraph\Exceptions\TelegramWebhookException;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Http\Request;

abstract class Callback
{
    // all extended classes need initialize this field
    public static string $name;

    protected int $messageId;
    protected int $callbackQueryId;
    protected Keyboard $originalKeyboard;

    public function __construct(
        protected TelegraphBot $bot,
        protected TelegraphChat $chat,
        protected CallbackQuery $callbackQuery,
        protected Request $request,
    ) {
        $this->callbackQueryId = $this->callbackQuery->id();
        $this->messageId = $this->callbackQuery->message()?->id()
            ?? throw TelegramWebhookException::invalidData('message id missing');
        /** @phpstan-ignore-next-line */
        $this->originalKeyboard = $this->callbackQuery->message()?->keyboard() ?? Keyboard::make();
    }

    abstract public function handle(): void;
}

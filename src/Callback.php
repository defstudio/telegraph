<?php

namespace DefStudio\Telegraph;

use DefStudio\Telegraph\DTO\CallbackQuery;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Http\Request;

abstract class Callback
{
    // all extended classes need initialize this field
    public static string $name;

    protected Keyboard $originalKeyboard;

    public function __construct(
        protected TelegraphBot $bot,
        protected TelegraphChat $chat,
        protected CallbackQuery $callbackQuery,
        protected Request $request,
        protected int $messageId,
        protected int $callbackQueryId,
    ) {
        $this->originalKeyboard = Keyboard::make();
    }

    abstract public function handle(): void;
}

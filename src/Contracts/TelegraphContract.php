<?php

namespace DefStudio\Telegraph\Contracts;

use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Http\Client\Response;

interface TelegraphContract
{
    public function bot(TelegraphBot $bot): Telegraph;

    public function chat(TelegraphChat $chat): Telegraph;

    public function html(string $message): Telegraph;

    public function markdown(string $message): Telegraph;

    /**
     * @param array<array<array<string, string>>> $keyboard
     */
    public function keyboard(array $keyboard): Telegraph;

    public function registerWebhook(): Telegraph;

    public function replyWebhook(int $callbackQueryId, string $message): Telegraph;

    public function replaceKeyboard(int $messageId, Keyboard $newKeyboard): Telegraph;

    public function deleteKeyboard(int $messageId): Telegraph;

    public function send(): Response;

    public function dispatch(string $queue = null): PendingDispatch;
}

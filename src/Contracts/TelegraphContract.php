<?php

namespace DefStudio\Telegraph\Contracts;

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

    public function replyWebhook(string $callbackQueryId, string $message): Telegraph;

    /**
     * @param array<array<array<string, string>>> $newKeyboard
     */
    public function replaceKeyboard(string $messageId, array $newKeyboard): Telegraph;

    public function send(): Response;

    public function dispatch(string $queue = null): PendingDispatch;
}

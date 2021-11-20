<?php

namespace DefStudio\LaravelTelegraph\Contracts;

use DefStudio\LaravelTelegraph\LaravelTelegraph;
use Illuminate\Http\Client\Response;

interface TelegraphContract
{
    public function bot(string $botToken): LaravelTelegraph;

    public function chat(string $chatId): LaravelTelegraph;

    public function html(string $message): LaravelTelegraph;

    public function markdown(string $message): LaravelTelegraph;

    /**
     * @param array<array<array<string, string>>> $keyboard
     */
    public function keyboard(array $keyboard): LaravelTelegraph;

    public function registerWebhook(): LaravelTelegraph;

    public function replyWebhook(string $callbackQueryId, string $message): LaravelTelegraph;

    /**
     * @param array<array<array<string, string>>> $newKeyboard
     */
    public function replaceKeyboard(string $messageId, array $newKeyboard): LaravelTelegraph;

    public function send(): Response;
}

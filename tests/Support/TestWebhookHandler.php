<?php

/** @noinspection PhpUnusedPrivateMethodInspection */

namespace DefStudio\LaravelTelegraph\Tests\Support;

use DefStudio\LaravelTelegraph\Handlers\WebhookHandler;

class TestWebhookHandler extends WebhookHandler
{
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

    public function send_reply(): void
    {
        $this->reply('foo');
    }

    public function change_keyboard(): void
    {
        $this->replaceKeyboard([
            [
                ["text" => "test", "callback_data" => "action:test;id:1"],
                ["text" => "delete", "callback_data" => "action:delete;id:2"],
            ],
        ]);
    }

    public function delete_keyboard(): void
    {
        $this->deleteKeyboard();
    }

    private function private_action(): void
    {
    }

    protected function extractData(): void
    {
        parent::extractData();

        self::$extracted_data = [
            'chatId' => $this->chatId,
            'messageId' => $this->messageId,
            'callbackQueryId' => $this->callbackQueryId,
            'originalKeyboard' => $this->originalKeyboard,
            'data' => $this->data,
        ];
    }
}

<?php

/** @noinspection PhpUnusedPrivateMethodInspection */

namespace DefStudio\Telegraph\Tests\Support;

use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;

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
}

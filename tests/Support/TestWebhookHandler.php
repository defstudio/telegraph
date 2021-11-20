<?php

namespace DefStudio\LaravelTelegraph\Tests\Support;

use DefStudio\LaravelTelegraph\Handlers\WebhookHandler;

class TestWebhookHandler extends WebhookHandler
{
    public static int $calls_count = 0;

    public static function reset()
    {
        self::$calls_count = 0;
    }

    public function test(): void
    {
        self::$calls_count++;
    }
}

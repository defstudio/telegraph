<?php

namespace DefStudio\LaravelTelegraph\Facades;

use DefStudio\LaravelTelegraph\Support\Testing\Fakes\LaravelTelegraphFake;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string getUrl()
 * @method static \DefStudio\LaravelTelegraph\LaravelTelegraph  bot(string $botToken)
 * @method static \DefStudio\LaravelTelegraph\LaravelTelegraph  chat(string $chatId)
 * @method static \DefStudio\LaravelTelegraph\LaravelTelegraph  html(string $message)
 * @method static \DefStudio\LaravelTelegraph\LaravelTelegraph  markdown(string $message)
 * @method static \DefStudio\LaravelTelegraph\LaravelTelegraph  keyboard(array $keyboard)
 * @method static \DefStudio\LaravelTelegraph\LaravelTelegraph  registerWebhook()
 * @method static \DefStudio\LaravelTelegraph\LaravelTelegraph  getWebhookDebugInfo()
 * @method static \DefStudio\LaravelTelegraph\LaravelTelegraph  replyWebhook(string $callbackQueryId, string $message)
 * @method static \DefStudio\LaravelTelegraph\LaravelTelegraph  replaceKeyboard(string $messageId, array $newKeyboard)
 * @method static \DefStudio\LaravelTelegraph\LaravelTelegraph  send()
 * @method static void  assertSentData(string $endpoint, array $data = null)
 * @method static void  assertSent(string $message)
 * @method static void  assertRegisteredWebhook()
 * @method static void  assertRequestedWebhookDebugInfo()
 * @method static void  assertRepliedWebhook(string $message)
 *
 * @see \DefStudio\LaravelTelegraph\LaravelTelegraph
 */
class LaravelTelegraph extends Facade
{
    /**
     * @param array<string, array<mixed>> $replies
     */
    public static function fake(array $replies = []): LaravelTelegraphFake
    {
        static::swap($fake = new LaravelTelegraphFake($replies));

        return $fake;
    }

    protected static function getFacadeAccessor(): string
    {
        return 'laravel-telegraph';
    }
}

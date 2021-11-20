<?php

namespace DefStudio\LaravelTelegraph\Facades;

use DefStudio\LaravelTelegraph\Support\Testing\Fakes\LaravelTelegraphFake;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string getUrl()
 * @method \DefStudio\LaravelTelegraph\LaravelTelegraph  bot(string $botToken)
 * @method \DefStudio\LaravelTelegraph\LaravelTelegraph  chat(string $chatId)
 * @method \DefStudio\LaravelTelegraph\LaravelTelegraph  html(string $message)
 * @method \DefStudio\LaravelTelegraph\LaravelTelegraph  markdown(string $message)
 * @method \DefStudio\LaravelTelegraph\LaravelTelegraph  keyboard(array $keyboard)
 * @method \DefStudio\LaravelTelegraph\LaravelTelegraph  registerWebhook()
 * @method \DefStudio\LaravelTelegraph\LaravelTelegraph  getWebhookDebugInfo()
 * @method \DefStudio\LaravelTelegraph\LaravelTelegraph  replyWebhook(string $callbackQueryId, string $message)
 * @method \DefStudio\LaravelTelegraph\LaravelTelegraph  replaceKeyboard(string $messageId, array $newKeyboard)
 * @method \DefStudio\LaravelTelegraph\LaravelTelegraph  send()
 *
 * @see \DefStudio\LaravelTelegraph\LaravelTelegraph
 */
class LaravelTelegraph extends Facade
{
    public static function fake(): LaravelTelegraphFake
    {
        static::swap($fake = new LaravelTelegraphFake());

        return $fake;
    }

    protected static function getFacadeAccessor(): string
    {
        return 'laravel-telegraph';
    }
}

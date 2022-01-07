<?php

namespace DefStudio\Telegraph\Facades;

use DefStudio\Telegraph\Support\Testing\Fakes\TelegraphFake;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string getUrl()
 * @method static \DefStudio\Telegraph\Telegraph  bot(string $botToken)
 * @method static \DefStudio\Telegraph\Telegraph  chat(string $chatId)
 * @method static \DefStudio\Telegraph\Telegraph  message(string $message)
 * @method static \DefStudio\Telegraph\Telegraph  html(string $message)
 * @method static \DefStudio\Telegraph\Telegraph  markdown(string $message)
 * @method static \DefStudio\Telegraph\Telegraph  registerWebhook()
 * @method static \DefStudio\Telegraph\Telegraph  getWebhookDebugInfo()
 * @method static \DefStudio\Telegraph\Telegraph  replyWebhook(string $callbackQueryId, string $message)
 * @method static \DefStudio\Telegraph\Telegraph  replaceKeyboard(string $messageId, array $newKeyboard)
 * @method static \DefStudio\Telegraph\Telegraph  deleteKeyboard(string $messageId)
 * @method static \DefStudio\Telegraph\Telegraph  send()
 * @method static void  dumpSentData()
 * @method static void  assertSentData(string $endpoint, array $data = null)
 * @method static void  assertSent(string $message, bool $exact = true)
 * @method static void  assertRegisteredWebhook()
 * @method static void  assertRequestedWebhookDebugInfo()
 * @method static void  assertRepliedWebhook(string $message)
 *
 * @see \DefStudio\Telegraph\Telegraph
 */
class Telegraph extends Facade
{
    /**
     * @param array<string, array<mixed>> $replies
     */
    public static function fake(array $replies = []): TelegraphFake
    {
        static::swap($fake = new TelegraphFake($replies));

        return $fake;
    }

    protected static function getFacadeAccessor(): string
    {
        return 'telegraph';
    }
}

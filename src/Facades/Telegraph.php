<?php

namespace DefStudio\Telegraph\Facades;

use DefStudio\Telegraph\Contracts\Downloadable;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use DefStudio\Telegraph\Support\Testing\Fakes\TelegraphFake;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string getUrl()
 * @method static \DefStudio\Telegraph\Telegraph  bot(TelegraphBot $bot)
 * @method static \DefStudio\Telegraph\Telegraph  chat(TelegraphChat $chat)
 * @method static \DefStudio\Telegraph\Telegraph  message(string $message)
 * @method static \DefStudio\Telegraph\Telegraph  html(string $message)
 * @method static \DefStudio\Telegraph\Telegraph  reply(int $messageId)
 * @method static \DefStudio\Telegraph\Telegraph  edit(string $messageId)
 * @method static \DefStudio\Telegraph\Telegraph  markdown(string $message)
 * @method static \DefStudio\Telegraph\Telegraph  registerWebhook()
 * @method static \DefStudio\Telegraph\Telegraph  unregisterWebhook(bool $dropPendingUpdates = false)
 * @method static \DefStudio\Telegraph\Telegraph  registerBotCommands(array $commands)
 * @method static \DefStudio\Telegraph\Telegraph  unregisterBotCommands()
 * @method static \DefStudio\Telegraph\Telegraph  getWebhookDebugInfo()
 * @method static \DefStudio\Telegraph\Telegraph  replyWebhook(string $callbackQueryId, string $message)
 * @method static \DefStudio\Telegraph\Telegraph  replaceKeyboard(string $messageId, Keyboard|callable $newKeyboard)
 * @method static \DefStudio\Telegraph\Telegraph  deleteKeyboard(string $messageId)
 * @method static \DefStudio\Telegraph\Telegraph  deleteMessage(string $messageId)
 * @method static \DefStudio\Telegraph\Telegraph  pinMessage(string $messageId)
 * @method static \DefStudio\Telegraph\Telegraph  unpinMessage(string $messageId)
 * @method static \DefStudio\Telegraph\Telegraph  unpinAllMessages()
 * @method static \DefStudio\Telegraph\Telegraph  editCaption(string $messageId)
 * @method static \DefStudio\Telegraph\Telegraph  answerInlineQuery(string $inlineQueryID, array $results)
 * @method static \DefStudio\Telegraph\Telegraph  document(string $path, string $filename = null)
 * @method static \DefStudio\Telegraph\Telegraph  photo(string $path, string $filename = null)
 * @method static \DefStudio\Telegraph\Telegraph  voice(string $path, string $filename = null)
 * @method static \DefStudio\Telegraph\Telegraph  location(float $latitude, float $longitude)
 * @method static \DefStudio\Telegraph\Telegraph  botUpdates()
 * @method static \DefStudio\Telegraph\Telegraph  botInfo()
 * @method static string store(Downloadable $attachment, string $path, string $filename = null)
 * @method static void  dumpSentData()
 * @method static void  assertSentData(string $endpoint, array $data = null, bool $exact = true)
 * @method static void  assertSentFiles(string $endpoint, array $files = null)
 * @method static void  assertSent(string $message, bool $exact = true)
 * @method static void  assertNothingSent()
 * @method static void  assertRegisteredWebhook()
 * @method static void  assertUnregisteredWebhook()
 * @method static void  assertRequestedWebhookDebugInfo()
 * @method static void  assertRepliedWebhook(string $message)
 * @method static void  assertStoredFile(string $fileId)
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
        TelegraphFake::reset();
        static::swap($fake = new TelegraphFake($replies));

        return $fake;
    }

    protected static function getFacadeAccessor(): string
    {
        return 'telegraph';
    }
}

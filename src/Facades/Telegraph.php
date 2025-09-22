<?php

namespace DefStudio\Telegraph\Facades;

use DefStudio\Telegraph\Contracts\Downloadable;
use DefStudio\Telegraph\DTO\InlineQueryResult;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use DefStudio\Telegraph\Payments\TelegraphInvoicePayload;
use DefStudio\Telegraph\ScopedPayloads\SetChatMenuButtonPayload;
use DefStudio\Telegraph\ScopedPayloads\TelegraphPollPayload;
use DefStudio\Telegraph\ScopedPayloads\TelegraphQuizPayload;
use DefStudio\Telegraph\Support\Testing\Fakes\TelegraphFake;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string getUrl()
 * @method static \DefStudio\Telegraph\Telegraph  bot(TelegraphBot|string $bot)
 * @method static \DefStudio\Telegraph\Telegraph  chat(TelegraphChat|string $chat)
 * @method static \DefStudio\Telegraph\Telegraph  message(string $message)
 * @method static \DefStudio\Telegraph\Telegraph  withEndpoint(string $endpoint)
 * @method static \DefStudio\Telegraph\Telegraph  withData(string $key, mixed $value)
 * @method static \DefStudio\Telegraph\Telegraph  inThread(int $thread_id)
 * @method static \DefStudio\Telegraph\Telegraph  html(string $message)
 * @method static \DefStudio\Telegraph\Telegraph  reply(int $messageId)
 * @method static \DefStudio\Telegraph\Telegraph  edit(string $messageId)
 * @method static \DefStudio\Telegraph\Telegraph  markdown(string $message)
 * @method static \DefStudio\Telegraph\Telegraph  markdownV2(string $message)
 * @method static \DefStudio\Telegraph\Telegraph  registerWebhook()
 * @method static \DefStudio\Telegraph\Telegraph  unregisterWebhook(bool $dropPendingUpdates = false)
 * @method static \DefStudio\Telegraph\Telegraph  registerBotCommands(array<string, string> $commands)
 * @method static \DefStudio\Telegraph\Telegraph  getRegisteredCommands()
 * @method static \DefStudio\Telegraph\Telegraph  unregisterBotCommands()
 * @method static \DefStudio\Telegraph\Telegraph  getWebhookDebugInfo()
 * @method static \DefStudio\Telegraph\Telegraph  replyWebhook(string $callbackQueryId, string $message)
 * @method static \DefStudio\Telegraph\Telegraph  replaceKeyboard(string $messageId, Keyboard|callable $newKeyboard)
 * @method static \DefStudio\Telegraph\Telegraph  deleteKeyboard(string $messageId)
 * @method static \DefStudio\Telegraph\Telegraph  deleteMessage(string $messageId)
 * @method static \DefStudio\Telegraph\Telegraph  forwardMessage($fromChat, $messageId)
 * @method static \DefStudio\Telegraph\Telegraph  pinMessage(string $messageId)
 * @method static \DefStudio\Telegraph\Telegraph  unpinMessage(string $messageId)
 * @method static \DefStudio\Telegraph\Telegraph  unpinAllMessages()
 * @method static \DefStudio\Telegraph\Telegraph  editCaption(string $messageId)
 * @method static \DefStudio\Telegraph\Telegraph  editMedia(string $messageId)
 * @method static \DefStudio\Telegraph\Telegraph  answerInlineQuery(string $inlineQueryID, InlineQueryResult[] $results)
 * @method static \DefStudio\Telegraph\Telegraph  document(string $path, string $filename = null)
 * @method static \DefStudio\Telegraph\Telegraph  photo(string $path, string $filename = null)
 * @method static \DefStudio\Telegraph\Telegraph  animation(string $path, string $filename = null)
 * @method static \DefStudio\Telegraph\Telegraph  voice(string $path, string $filename = null)
 * @method static \DefStudio\Telegraph\Telegraph  location(float $latitude, float $longitude)
 * @method static \DefStudio\Telegraph\Telegraph  contact(string $phoneNumber, string $firstName)
 * @method static \DefStudio\Telegraph\Telegraph  video(string $path, string $filename = null)
 * @method static \DefStudio\Telegraph\Telegraph  audio(string $path, string $filename = null)
 * @method static \DefStudio\Telegraph\Telegraph  dice()
 * @method static \DefStudio\Telegraph\Telegraph  mediaGroup(array<int|string, array<mixed>> $media)
 * @method static \DefStudio\Telegraph\Telegraph  botUpdates()
 * @method static \DefStudio\Telegraph\Telegraph  botInfo()
 * @method static \DefStudio\Telegraph\Telegraph  setBaseUrl(string|null $url)
 * @method static \DefStudio\Telegraph\Telegraph  setTitle(string $title)
 * @method static \DefStudio\Telegraph\Telegraph  setDescription(string $description)
 * @method static \DefStudio\Telegraph\Telegraph  setChatPhoto(string $path)
 * @method static \DefStudio\Telegraph\Telegraph  setMessageReaction(int $message_id, array<string, string> $reaction, bool $isBig = false)
 * @method static \DefStudio\Telegraph\Telegraph  reactWithEmoji(int $message_id, string $emoji, bool $isBig = false)
 * @method static \DefStudio\Telegraph\Telegraph  reactWithCustomEmoji(int $message_id, string $customEmoji, bool $isBig = false)
 * @method static \DefStudio\Telegraph\Telegraph  chatInfo()
 * @method static \DefStudio\Telegraph\Telegraph  generateChatPrimaryInviteLink()
 * @method static \DefStudio\Telegraph\Telegraph  createChatInviteLink()
 * @method static \DefStudio\Telegraph\Telegraph  editChatInviteLink()
 * @method static \DefStudio\Telegraph\Telegraph  revokeChatInviteLink()
 * @method static \DefStudio\Telegraph\Telegraph  chatMemberCount()
 * @method static \DefStudio\Telegraph\Telegraph  chatMember(string $userId)
 * @method static \DefStudio\Telegraph\Telegraph  setChatPermissions(array<int|string, string|bool> $permissions)
 * @method static \DefStudio\Telegraph\Telegraph  banChatMember(string $userId)
 * @method static \DefStudio\Telegraph\Telegraph  unbanChatMember(string $userId)
 * @method static \DefStudio\Telegraph\Telegraph  restrictChatMember(string $userId, array<int|string, string|bool> $permissions)
 * @method static \DefStudio\Telegraph\Telegraph  promoteChatMember(string $userId, array<int|string, string|bool> $permissions)
 * @method static \DefStudio\Telegraph\Telegraph  demoteChatMember(string $userId)
 * @method static \DefStudio\Telegraph\Telegraph  userProfilePhotos(string $userId)
 * @method static \DefStudio\Telegraph\Telegraph  chatMenuButton()
 * @method static \DefStudio\Telegraph\Telegraph  createForumTopic(string $name, int $iconColor = null, string $iconCustomEmojiId = null)
 * @method static \DefStudio\Telegraph\Telegraph  editForumTopic(int $threadId = null, string $name = null, string $iconCustomEmojiId = null)
 * @method static \DefStudio\Telegraph\Telegraph  closeForumTopic(int $threadId = null)
 * @method static \DefStudio\Telegraph\Telegraph  reopenForumTopic(int $threadId = null)
 * @method static \DefStudio\Telegraph\Telegraph  deleteForumTopic(int $threadId = null)
 * @method static TelegraphInvoicePayload  invoice(string $title)
 * @method static SetChatMenuButtonPayload  setChatMenuButton()
 * @method static TelegraphPollPayload poll(string $question)
 * @method static TelegraphQuizPayload quiz(string $question)
 * @method static string store(Downloadable $attachment, string $path, string $filename = null)
 * @method static void  dumpSentData()
 * @method static void  assertSentData(string $endpoint, mixed[] $data = null, bool $exact = true)
 * @method static void  assertSentFiles(string $endpoint, mixed[] $files = null)
 * @method static void  assertSent(string $message, bool $exact = true)
 * @method static void  assertNotSent(string $message, bool $exact = true)
 * @method static void  assertNothingSent()
 * @method static void  assertRegisteredWebhook(mixed[] $data = null, bool $exact = true)
 * @method static void  assertUnregisteredWebhook(mixed[] $data = null, bool $exact = true)
 * @method static void  assertRequestedWebhookDebugInfo(mixed[] $data = null, bool $exact = true)
 * @method static void  assertRepliedWebhook(string $message)
 * @method static void  assertRepliedWebhookIsAlert()
 * @method static void  assertStoredFile(string $fileId)
 * @method static void  assertChatJoinRequestApproved(string $userId)
 *
 * @see \DefStudio\Telegraph\Telegraph
 */
class Telegraph extends Facade
{
    protected static $cached = false;

    /**
     * @param array<string, array<mixed>> $replies
     */
    public static function fake(array $replies = []): TelegraphFake
    {
        TelegraphFake::reset();
        static::swap($fake = new TelegraphFake($replies));

        return $fake;
    }

    public static function getFacadeRoot()
    {
        $instance = parent::getFacadeRoot();
        if ($instance instanceof TelegraphFake) {
            $instance->prepareForNewRequest();
        }

        return $instance;
    }

    protected static function getFacadeAccessor(): string
    {
        return 'telegraph';
    }
}

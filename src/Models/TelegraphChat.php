<?php

/** @noinspection PhpUnused */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

namespace DefStudio\Telegraph\Models;

use DefStudio\Telegraph\Concerns\HasStorage;
use DefStudio\Telegraph\Contracts\Storable;
use DefStudio\Telegraph\Database\Factories\TelegraphChatFactory;
use DefStudio\Telegraph\DTO\ChatMember;
use DefStudio\Telegraph\Exceptions\TelegraphException;
use DefStudio\Telegraph\Facades\Telegraph as TelegraphFacade;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Payments\TelegraphInvoicePayload;
use DefStudio\Telegraph\ScopedPayloads\SetChatMenuButtonPayload;
use DefStudio\Telegraph\ScopedPayloads\TelegraphPollPayload;
use DefStudio\Telegraph\ScopedPayloads\TelegraphQuizPayload;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * DefStudio\Telegraph\Models\TelegraphChat
 *
 * @property int $id
 * @property string $chat_id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read TelegraphBot $bot
 */
class TelegraphChat extends Model implements Storable
{
    /** @use HasFactory<TelegraphChatFactory> */
    use HasFactory;
    use HasStorage;

    protected $fillable = [
        'chat_id',
        'name',
    ];

    /**
     * @return TelegraphChatFactory
     */
    protected static function newFactory(): Factory
    {
        return TelegraphChatFactory::new();
    }

    public static function booted(): void
    {
        self::created(function (TelegraphChat $chat) {
            if (empty($chat->name)) {
                $chat->name = "Chat #$chat->id";
                $chat->saveQuietly();
            }
        });
    }

    public function storageKey(): string|int
    {
        return $this->id;
    }

    /**
     * @return BelongsTo<TelegraphBot, TelegraphChat>
     */
    public function bot(): BelongsTo
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsTo(config('telegraph.models.bot'), 'telegraph_bot_id');
    }

    /**
     * @return array{id: int, type: string, title?: string, description?: string, username?: string, first_name?: string, last_name?: string, photo?: array<string, mixed>, pinned_message?: array<string, mixed>, permissions?: array<string, mixed>, bio?: string, has_private_forwards?: true, has_restricted_voice_and_video_messages?: true, join_to_send_messages?: true, join_by_request?: true, has_protected_content?: true, invite_link?: string, sticker_set_name?: string, sticker_set_name?: true, linked_chat_id?: int, slow_mode_delay?: int, location?: array<string, mixed>, message_auto_delete_time?: int}
     */
    public function info(): array
    {
        $reply = TelegraphFacade::chat($this)->chatInfo()->send();

        if ($reply->telegraphError()) {
            throw TelegraphException::failedToRetrieveChatInfo();
        }

        /* @phpstan-ignore-next-line */
        return $reply->json('result');
    }

    public function memberCount(): int
    {
        $reply = TelegraphFacade::chat($this)->chatMemberCount()->send();

        if ($reply->telegraphError()) {
            throw TelegraphException::failedToRetrieveChatInfo();
        }

        /* @phpstan-ignore-next-line */
        return $reply->json('result');
    }

    public function memberInfo(string $user_id): null|ChatMember
    {
        $reply = TelegraphFacade::chat($this)->chatMember($user_id)->send();

        if ($reply->telegraphError()) {
            throw TelegraphException::failedToRetrieveChatInfo();
        }

        if (!$reply->json('result')) {
            return null;
        }

        /* @phpstan-ignore-next-line */
        return ChatMember::fromArray($reply->json('result'));
    }

    public function withEndpoint(string $endpoint): Telegraph
    {
        return TelegraphFacade::chat($this)->withEndpoint($endpoint);
    }

    public function withData(string $key, mixed $value): Telegraph
    {
        return TelegraphFacade::chat($this)->withData($key, $value);
    }

    public function inThread(int $thread_id): Telegraph
    {
        return TelegraphFacade::chat($this)->inThread($thread_id);
    }

    public function message(string $message): Telegraph
    {
        return TelegraphFacade::chat($this)->message($message);
    }

    public function html(string $message): Telegraph
    {
        return TelegraphFacade::chat($this)->html($message);
    }

    public function markdown(string $message): Telegraph
    {
        return TelegraphFacade::chat($this)->markdown($message);
    }

    public function markdownV2(string $message): Telegraph
    {
        return TelegraphFacade::chat($this)->markdownV2($message);
    }

    public function reply(int $messageId): Telegraph
    {
        return TelegraphFacade::chat($this)->reply($messageId);
    }

    /**
     * @param Keyboard|callable(Keyboard):Keyboard $newKeyboard
     */
    public function replaceKeyboard(int $messageId, Keyboard|callable $newKeyboard): Telegraph
    {
        return TelegraphFacade::chat($this)->replaceKeyboard($messageId, $newKeyboard);
    }

    public function deleteKeyboard(int $messageId): Telegraph
    {
        return TelegraphFacade::chat($this)->deleteKeyboard($messageId);
    }

    public function edit(int $messageId): Telegraph
    {
        return TelegraphFacade::chat($this)->edit($messageId);
    }

    public function editCaption(int $messageId): Telegraph
    {
        return TelegraphFacade::chat($this)->editCaption($messageId);
    }

    public function editMedia(int $messageId): Telegraph
    {
        return TelegraphFacade::chat($this)->editMedia($messageId);
    }

    public function deleteMessage(int $messageId): Telegraph
    {
        return TelegraphFacade::chat($this)->deleteMessage($messageId);
    }

    /**
     * @param array<int> $messageIds
     */
    public function deleteMessages(array $messageIds): Telegraph
    {
        return TelegraphFacade::chat($this)->deleteMessages($messageIds);
    }

    public function pinMessage(int $messageId): Telegraph
    {
        return TelegraphFacade::chat($this)->pinMessage($messageId);
    }

    public function unpinMessage(int $messageId): Telegraph
    {
        return TelegraphFacade::chat($this)->unpinMessage($messageId);
    }

    public function unpinAllMessages(): Telegraph
    {
        return TelegraphFacade::chat($this)->unpinAllMessages();
    }

    public function leave(): Telegraph
    {
        return TelegraphFacade::chat($this)->leaveChat();
    }

    public function action(string $action): Telegraph
    {
        return TelegraphFacade::chat($this)->chatAction($action);
    }

    public function document(string $path, string|null $filename = null): Telegraph
    {
        return TelegraphFacade::chat($this)->document($path, $filename);
    }

    public function location(float $latitude, float $longitude): Telegraph
    {
        return TelegraphFacade::chat($this)->location(latitude: $latitude, longitude: $longitude);
    }

    public function photo(string $path, string|null $filename = null): Telegraph
    {
        return TelegraphFacade::chat($this)->photo($path, $filename);
    }

    /**
     * @param array<int|string, array<mixed>> $media
     */
    public function mediaGroup(array $media): Telegraph
    {
        return TelegraphFacade::chat($this)->mediaGroup($media);
    }

    public function sticker(string $path, string|null $filename = null): Telegraph
    {
        return TelegraphFacade::chat($this)->sticker($path, $filename);
    }

    public function venue(float $latitude, float $longitude, string $title, string $address): Telegraph
    {
        return TelegraphFacade::chat($this)->venue($latitude,  $longitude,  $title,  $address);
    }

    public function animation(string $path, string|null $filename = null): Telegraph
    {
        return TelegraphFacade::chat($this)->animation($path, $filename);
    }

    public function video(string $path, string|null $filename = null): Telegraph
    {
        return TelegraphFacade::chat($this)->video($path, $filename);
    }

    public function audio(string $path, string|null $filename = null): Telegraph
    {
        return TelegraphFacade::chat($this)->audio($path, $filename);
    }

    public function voice(string $path, string|null $filename = null): Telegraph
    {
        return TelegraphFacade::chat($this)->voice($path, $filename);
    }

    public function contact(string $phoneNumber, string $firstName): Telegraph
    {
        return TelegraphFacade::chat($this)->contact($phoneNumber, $firstName);
    }

    public function setBaseUrl(string|null $url): Telegraph
    {
        return TelegraphFacade::chat($this)->setBaseUrl($url);
    }

    public function setTitle(string $title): Telegraph
    {
        return TelegraphFacade::chat($this)->setTitle($title);
    }

    public function setDescription(string $description): Telegraph
    {
        return TelegraphFacade::chat($this)->setDescription($description);
    }

    public function setChatPhoto(string $path): Telegraph
    {
        return TelegraphFacade::chat($this)->setChatPhoto($path);
    }

    /**
     * @param array<string, string> $reaction
     */
    public function setMessageReaction(int $messageId, array $reaction, bool $isBig = false): Telegraph
    {
        return TelegraphFacade::chat($this)->setMessageReaction($messageId, $reaction, $isBig);
    }

    public function reactWithEmoji(int $messageId, string $emoji, bool $isBig = false): Telegraph
    {
        $reaction = ['type' => 'emoji', 'emoji' => $emoji];

        return $this->setMessageReaction($messageId, $reaction, $isBig);
    }

    public function reactWithCustomEmoji(int $messageId, string $customEmoji, bool $isBig = false): Telegraph
    {
        $reaction = ['type' => 'custom_emoji', 'emoji' => $customEmoji];

        return $this->setMessageReaction($messageId, $reaction, $isBig);
    }

    public function deleteChatPhoto(): Telegraph
    {
        return TelegraphFacade::chat($this)->deleteChatPhoto();
    }

    public function generatePrimaryInviteLink(): Telegraph
    {
        return TelegraphFacade::chat($this)->generateChatPrimaryInviteLink();
    }

    public function createInviteLink(): Telegraph
    {
        return TelegraphFacade::chat($this)->createChatInviteLink();
    }

    public function editInviteLink(string $link): Telegraph
    {
        return TelegraphFacade::chat($this)->editChatInviteLink($link);
    }

    public function revokeInviteLink(string $link): Telegraph
    {
        return TelegraphFacade::chat($this)->revokeChatInviteLink($link);
    }

    /**
     * @param array<int|string, string|bool> $permissions
     */
    public function setPermissions(array $permissions): Telegraph
    {
        return TelegraphFacade::chat($this)->setChatPermissions($permissions);
    }

    public function banMember(string $userId): Telegraph
    {
        return TelegraphFacade::chat($this)->banChatMember($userId);
    }

    public function unbanMember(string $userId, bool $onlyIfBanned = true): Telegraph
    {
        return TelegraphFacade::chat($this)->unbanChatMember($userId, $onlyIfBanned);
    }

    /**
     * @param array<int|string, string|bool> $permissions
     */
    public function restrictMember(string $userId, array $permissions): Telegraph
    {
        return TelegraphFacade::chat($this)->restrictChatMember($userId, $permissions);
    }

    /**
     * @param array<int|string, string|bool> $permissions
     */
    public function promoteMember(string $userId, array $permissions): Telegraph
    {
        return TelegraphFacade::chat($this)->promoteChatMember($userId, $permissions);
    }

    public function demoteMember(string $userId): Telegraph
    {
        return TelegraphFacade::chat($this)->demoteChatMember($userId);
    }

    public function approveJoinRequest(string $userId): Telegraph
    {
        return TelegraphFacade::chat($this)->approveChatJoinRequest($userId);
    }

    public function declineJoinRequest(string $userId): Telegraph
    {
        return TelegraphFacade::chat($this)->declineChatJoinRequest($userId);
    }

    public function poll(string $question): TelegraphPollPayload
    {
        return TelegraphFacade::chat($this)->poll($question);
    }

    public function quiz(string $question): TelegraphQuizPayload
    {
        return TelegraphFacade::chat($this)->quiz($question);
    }

    public function invoice(string $title): TelegraphInvoicePayload
    {
        return TelegraphFacade::chat($this)->invoice($title);
    }

    public function dice(string|null $emoji = null): Telegraph
    {
        return TelegraphFacade::chat($this)->dice($emoji);
    }

    public function forwardMessage(TelegraphChat|int $fromChat, int $messageId): Telegraph
    {
        return TelegraphFacade::chat($this)->forwardMessage($fromChat, $messageId);
    }

    public function menuButton(): Telegraph
    {
        return TelegraphFacade::chat($this)->chatMenuButton();
    }

    public function setMenuButton(): SetChatMenuButtonPayload
    {
        return TelegraphFacade::chat($this)->setChatMenuButton();
    }

    public function createForumTopic(string $name, int|null $iconColor = null, string|null $iconCustomEmojiId = null): Telegraph
    {
        return TelegraphFacade::chat($this)->createForumTopic($name, $iconColor, $iconCustomEmojiId);
    }

    public function editForumTopic(int|null $threadId = null, string|null $name = null, string|null $iconCustomEmojiId = null): Telegraph
    {
        return TelegraphFacade::chat($this)->editForumTopic($threadId, $name, $iconCustomEmojiId);
    }

    public function closeForumTopic(int|null $threadId = null): Telegraph
    {
        return TelegraphFacade::chat($this)->closeForumTopic($threadId);
    }

    public function reopenForumTopic(int|null $threadId = null): Telegraph
    {
        return TelegraphFacade::chat($this)->reopenForumTopic($threadId);
    }

    public function deleteForumTopic(int|null $threadId = null): Telegraph
    {
        return TelegraphFacade::chat($this)->deleteForumTopic($threadId);
    }

    public function copyMessage(TelegraphChat|int $fromChat, int $messageId): Telegraph
    {
        return TelegraphFacade::chat($this)->copyMessage($fromChat, $messageId);
    }
}

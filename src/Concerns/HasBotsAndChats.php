<?php

/** @noinspection DuplicatedCode */

/** @noinspection PhpDocMissingThrowsInspection */

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\DTO\Attachment;
use DefStudio\Telegraph\Enums\ChatActions;
use DefStudio\Telegraph\Enums\ChatAdminPermissions;
use DefStudio\Telegraph\Exceptions\ChatSettingsException;
use DefStudio\Telegraph\Exceptions\ChatThreadException;
use DefStudio\Telegraph\Exceptions\FileException;
use DefStudio\Telegraph\Exceptions\TelegraphException;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use DefStudio\Telegraph\ScopedPayloads\SetChatMenuButtonPayload;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;

/**
 * @mixin Telegraph
 */
trait HasBotsAndChats
{
    protected TelegraphBot|string|null $bot;

    protected TelegraphChat|string|null $chat;

    public function bot(TelegraphBot|string $bot): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->bot = $bot;

        return $telegraph;
    }

    public function chat(TelegraphChat|string $chat): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->chat = $chat;

        if (empty($telegraph->bot) && $chat instanceof TelegraphChat) {
            $telegraph->bot = $chat->bot;
        }

        return $telegraph;
    }

    protected function getBotIfAvailable(): TelegraphBot|string|null
    {
        $telegraph = clone $this;

        if (empty($telegraph->bot)) {
            /** @var TelegraphBot|string $bot */
            $bot = rescue(fn () => TelegraphBot::query()->sole(), config('telegraph.bot_token'), false);

            $telegraph->bot = $bot;
        }

        return $telegraph->bot;
    }

    protected function getBot(): TelegraphBot|string
    {
        return $this->getBotIfAvailable() ?? throw TelegraphException::missingBot();
    }

    protected function getBotToken(): string
    {
        $bot = $this->getBot();

        if ($bot instanceof TelegraphBot) {
            return $bot->token;
        }

        return $bot;
    }

    protected function getChatIfAvailable(): TelegraphChat|string|null
    {
        $telegraph = clone $this;

        if (empty($telegraph->chat)) {
            $bot = $telegraph->getBotIfAvailable();

            if ($bot instanceof TelegraphBot) {
                $telegraph->chat = rescue(fn () => $bot->chats()->sole(), report: false);
            }
        }

        if (empty($telegraph->chat)) {
            $telegraph->chat = rescue(fn () => TelegraphChat::query()->sole(), report: false);
        }

        return $telegraph->chat ?? null;
    }

    protected function getChat(): TelegraphChat|string
    {
        return $this->getChatIfAvailable() ?? throw TelegraphException::missingChat();
    }

    protected function getChatId(): string
    {
        $chat = $this->getChat();

        if ($chat instanceof TelegraphChat) {
            return $chat->chat_id;
        }

        return $chat;
    }

    protected function syncChatIdData(): void
    {
        $chat = $this->getChatIfAvailable();

        if ($chat === null) {
            return;
        }

        $this->data['chat_id'] = $chat instanceof TelegraphChat
            ? $chat->chat_id
            : $chat;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    protected function prepareChatData(array $data): array
    {
        $chat = $this->getChatIfAvailable();
        $shouldSetChatId = array_key_exists('chat_id', $data) || $this->endpointRequiresChat();

        if ($chat !== null && $shouldSetChatId) {
            $data['chat_id'] = $chat instanceof TelegraphChat
                ? $chat->chat_id
                : $chat;

            return $data;
        }

        if ($this->endpointRequiresChat() && !array_key_exists('chat_id', $data)) {
            throw TelegraphException::missingChat();
        }

        return $data;
    }

    protected function endpointRequiresChat(): bool
    {
        return in_array($this->endpoint ?? null, [
            self::ENDPOINT_REPLACE_KEYBOARD,
            self::ENDPOINT_MESSAGE,
            self::ENDPOINT_DELETE_MESSAGE,
            self::ENDPOINT_DELETE_MESSAGES,
            self::ENDPOINT_READ_BUSINESS_MESSAGE,
            self::ENDPOINT_DELETE_BUSINESS_MESSAGES,
            self::ENDPOINT_PIN_MESSAGE,
            self::ENDPOINT_UNPIN_MESSAGE,
            self::ENDPOINT_UNPIN_ALL_MESSAGES,
            self::ENDPOINT_EDIT_MESSAGE,
            self::ENDPOINT_EDIT_CAPTION,
            self::ENDPOINT_EDIT_MEDIA,
            self::ENDPOINT_SEND_LOCATION,
            self::ENDPOINT_SEND_ANIMATION,
            self::ENDPOINT_SEND_VOICE,
            self::ENDPOINT_SEND_MEDIA_GROUP,
            self::ENDPOINT_SEND_CHAT_ACTION,
            self::ENDPOINT_SEND_DOCUMENT,
            self::ENDPOINT_SEND_PHOTO,
            self::ENDPOINT_SEND_VIDEO,
            self::ENDPOINT_SEND_VIDEO_NOTE,
            self::ENDPOINT_SEND_AUDIO,
            self::ENDPOINT_SEND_CONTACT,
            self::ENDPOINT_SET_CHAT_TITLE,
            self::ENDPOINT_SET_CHAT_DESCRIPTION,
            self::ENDPOINT_SET_CHAT_PHOTO,
            self::ENDPOINT_SET_MESSAGE_REACTION,
            self::ENDPOINT_DELETE_CHAT_PHOTO,
            self::ENDPOINT_EXPORT_CHAT_INVITE_LINK,
            self::ENDPOINT_CREATE_CHAT_INVITE_LINK,
            self::ENDPOINT_CREATE_FORUM_TOPIC,
            self::ENDPOINT_EDIT_FORUM_TOPIC,
            self::ENDPOINT_CLOSE_FORUM_TOPIC,
            self::ENDPOINT_REOPEN_FORUM_TOPIC,
            self::ENDPOINT_DELETE_FORUM_TOPIC,
            self::ENDPOINT_EDIT_CHAT_INVITE_LINK,
            self::ENDPOINT_REVOKE_CHAT_INVITE_LINK,
            self::ENDPOINT_LEAVE_CHAT,
            self::ENDPOINT_GET_CHAT_INFO,
            self::ENDPOINT_GET_CHAT_MEMBER_COUNT,
            self::ENDPOINT_GET_CHAT_MEMBER,
            self::ENDPOINT_GET_CHAT_ADMINISTRATORS,
            self::ENDPOINT_SET_CHAT_PERMISSIONS,
            self::ENDPOINT_BAN_CHAT_MEMBER,
            self::ENDPOINT_UNBAN_CHAT_MEMBER,
            self::ENDPOINT_RESTRICT_CHAT_MEMBER,
            self::ENDPOINT_PROMOTE_CHAT_MEMBER,
            self::ENDPOINT_SEND_POLL,
            self::ENDPOINT_FORWARD_MESSAGE,
            self::ENDPOINT_COPY_MESSAGE,
            self::ENDPOINT_DICE,
            self::ENDPOINT_SEND_STICKER,
            self::ENDPOINT_SEND_VENUE,
            self::ENDPOINT_APPROVE_CHAT_JOIN_REQUEST,
            self::ENDPOINT_DECLINE_CHAT_JOIN_REQUEST,
            self::ENDPOINT_SEND_INVOICE,
            self::ENDPOINT_SEND_GAME,
        ], true);
    }

    public function leaveChat(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_LEAVE_CHAT;
        $telegraph->syncChatIdData();

        return $telegraph;
    }

    public function createForumTopic(string $name, ?int $iconColor = null, ?string $iconCustomEmojiId = null): Telegraph
    {
        $telegraph = clone $this;
        $telegraph->endpoint = self::ENDPOINT_CREATE_FORUM_TOPIC;
        $telegraph->syncChatIdData();
        $telegraph->data['name'] = $name;

        if ($iconColor !== null) {
            $telegraph->data['icon_color'] = $iconColor;
        }

        if ($iconCustomEmojiId !== null) {
            $telegraph->data['icon_custom_emoji_id'] = $iconCustomEmojiId;
        }

        return $telegraph;
    }

    public function editForumTopic(?int $threadId = null, ?string $name = null, ?string $iconCustomEmojiId = null): Telegraph
    {
        $telegraph = clone $this;

        if (!isset($telegraph->data['message_thread_id']) && $threadId === null) {
            throw ChatThreadException::emptyThreadId();
        }

        $telegraph->endpoint = self::ENDPOINT_EDIT_FORUM_TOPIC;
        $telegraph->syncChatIdData();

        if ($threadId !== null) {
            $telegraph->data['message_thread_id'] = $threadId;
        }

        if ($name !== null) {
            $telegraph->data['name'] = $name;
        }

        if ($iconCustomEmojiId !== null) {
            $telegraph->data['icon_custom_emoji_id'] = $iconCustomEmojiId;
        }

        return $telegraph;
    }

    public function closeForumTopic(?int $threadId = null): Telegraph
    {
        $telegraph = clone $this;

        if (!isset($telegraph->data['message_thread_id']) && $threadId === null) {
            throw ChatThreadException::emptyThreadId();
        }
        $telegraph->endpoint = self::ENDPOINT_CLOSE_FORUM_TOPIC;
        $telegraph->syncChatIdData();

        if ($threadId !== null) {
            $telegraph->data['message_thread_id'] = $threadId;
        }

        return $telegraph;
    }

    public function reopenForumTopic(?int $threadId = null): Telegraph
    {
        $telegraph = clone $this;

        if (!isset($telegraph->data['message_thread_id']) && $threadId === null) {
            throw ChatThreadException::emptyThreadId();
        }
        $telegraph->endpoint = self::ENDPOINT_REOPEN_FORUM_TOPIC;
        $telegraph->syncChatIdData();

        if ($threadId !== null) {
            $telegraph->data['message_thread_id'] = $threadId;
        }

        return $telegraph;
    }

    public function deleteForumTopic(?int $threadId = null): Telegraph
    {
        $telegraph = clone $this;

        if (!isset($telegraph->data['message_thread_id']) && $threadId === null) {
            throw ChatThreadException::emptyThreadId();
        }
        $telegraph->endpoint = self::ENDPOINT_DELETE_FORUM_TOPIC;
        $telegraph->syncChatIdData();

        if ($threadId !== null) {
            $telegraph->data['message_thread_id'] = $threadId;
        }

        return $telegraph;
    }

    public function botInfo(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_GET_BOT_INFO;

        return $telegraph;
    }

    /**
     * @param string[]|null $allowedUpdates
     */
    public function botUpdates(?int $timeout = null, ?int $offset = null, ?int $limit = null, ?array $allowedUpdates = null): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_GET_BOT_UPDATES;

        if ($offset !== null) {
            $telegraph->data['offset'] = $offset;
        }

        if ($limit !== null) {
            $telegraph->data['limit'] = $limit;
        }

        if ($timeout !== null) {
            $telegraph->data['timeout'] = $timeout;
        }

        if ($allowedUpdates !== null) {
            $telegraph->data['allowed_updates'] = $allowedUpdates;
        }

        return $telegraph;
    }

    public function chatAction(string $action): Telegraph
    {
        $telegraph = clone $this;

        in_array($action, ChatActions::available_actions()) || throw TelegraphException::invalidChatAction($action);

        $telegraph->endpoint = self::ENDPOINT_SEND_CHAT_ACTION;
        $telegraph->syncChatIdData();
        $telegraph->data['action'] = $action;

        return $telegraph;
    }

    public function setTitle(string $title): Telegraph
    {
        $telegraph = clone $this;

        !empty($title) || throw ChatSettingsException::emptyTitle();
        strlen($title) < 256 || throw ChatSettingsException::titleMaxLengthExceeded();

        $telegraph->endpoint = self::ENDPOINT_SET_CHAT_TITLE;
        $telegraph->syncChatIdData();
        $telegraph->data['title'] = $title;

        return $telegraph;
    }

    public function setDescription(string $description): Telegraph
    {
        $telegraph = clone $this;

        strlen($description) < 256 || throw ChatSettingsException::descriptionMaxLengthExceeded();

        $telegraph->endpoint = self::ENDPOINT_SET_CHAT_DESCRIPTION;
        $telegraph->syncChatIdData();
        $telegraph->data['description'] = $description;

        return $telegraph;
    }

    public function setChatPhoto(string $path): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_SET_CHAT_PHOTO;
        $telegraph->syncChatIdData();

        File::exists($path) || throw FileException::fileNotFound('photo', $path);

        $maxSizeInMb = config('telegraph.attachments.photo.max_size_mb', 10);

        assert(is_numeric($maxSizeInMb));

        if (($size = $telegraph->fileSizeInMb($path)) > $maxSizeInMb) {
            /* @phpstan-ignore-next-line */
            throw FileException::photoSizeExceeded($size, $maxSizeInMb);
        }

        $height = $telegraph->imageHeight($path);
        $width = $telegraph->imageWidth($path);

        $height_width_sum_px = config('telegraph.attachments.photo.height_width_sum_px', 10000);

        assert(is_numeric($height_width_sum_px));

        if (($totalLength = $height + $width) > $height_width_sum_px) {
            /* @phpstan-ignore-next-line */
            throw FileException::invalidPhotoSize($totalLength, $height_width_sum_px);
        }

        $maxRatio = config('telegraph.attachments.photo.max_ratio', 20);

        assert(is_numeric($maxRatio));

        if (($ratio = $height / $width) > $maxRatio || $ratio < (1 / $maxRatio)) {
            /* @phpstan-ignore-next-line */
            throw FileException::invalidPhotoRatio($ratio, $maxRatio);
        }

        $telegraph->files->put('photo', new Attachment($path));

        return $telegraph;
    }

    /**
     * @param array<string, string> $reaction
     */
    public function setMessageReaction(int $messageId, array $reaction, bool $isBig = false): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_SET_MESSAGE_REACTION;
        $telegraph->syncChatIdData();
        $telegraph->data['message_id'] = $messageId;
        $telegraph->data['reaction'] = json_encode([$reaction]);
        $telegraph->data['is_big'] = $isBig;

        return $telegraph;
    }

    public function reactWithEmoji(int $messageId, string $emoji, bool $isBig = false): Telegraph
    {
        return $this->setMessageReaction($messageId, ['type' => 'emoji', 'emoji' => $emoji], $isBig);
    }

    public function reactWithCustomEmoji(int $messageId, string $customEmoji, bool $isBig = false): Telegraph
    {
        return $this->setMessageReaction($messageId, ['type' => 'custom_emoji', 'emoji' => $customEmoji], $isBig);
    }

    public function deleteChatPhoto(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_DELETE_CHAT_PHOTO;
        $telegraph->syncChatIdData();

        return $telegraph;
    }

    public function chatInfo(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_GET_CHAT_INFO;
        $telegraph->syncChatIdData();

        return $telegraph;
    }

    public function setChatMenuButton(): SetChatMenuButtonPayload
    {
        $telegraph = clone $this;
        $telegraph->endpoint = self::ENDPOINT_SET_CHAT_MENU_BUTTON;

        if ($this->getChatIfAvailable() !== null) {
            $telegraph->syncChatIdData();
        }


        return SetChatMenuButtonPayload::makeFrom($telegraph);
    }

    public function chatMenuButton(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_GET_CHAT_MENU_BUTTON;

        return $telegraph;
    }

    public function generateChatPrimaryInviteLink(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_EXPORT_CHAT_INVITE_LINK;
        $telegraph->syncChatIdData();

        return $telegraph;
    }

    public function createChatInviteLink(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_CREATE_CHAT_INVITE_LINK;
        $telegraph->syncChatIdData();

        return $telegraph;
    }

    public function expire(Carbon $expiration): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->data['expire_date'] = $expiration->timestamp;

        return $telegraph;
    }

    public function name(string $name): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->data['name'] = $name;

        return $telegraph;
    }

    public function memberLimit(int $limit): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->data['member_limit'] = $limit;

        return $telegraph;
    }

    public function withJoinRequest(bool $enable = true): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->data['creates_join_request'] = $enable;

        return $telegraph;
    }

    public function editChatInviteLink(string $link): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_EDIT_CHAT_INVITE_LINK;
        $telegraph->syncChatIdData();
        $telegraph->data['invite_link'] = $link;

        return $telegraph;
    }

    public function revokeChatInviteLink(string $link): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_REVOKE_CHAT_INVITE_LINK;
        $telegraph->syncChatIdData();
        $telegraph->data['invite_link'] = $link;

        return $telegraph;
    }

    public function chatMemberCount(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_GET_CHAT_MEMBER_COUNT;
        $telegraph->syncChatIdData();

        return $telegraph;
    }

    public function chatMember(string $userId): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_GET_CHAT_MEMBER;
        $telegraph->syncChatIdData();
        $telegraph->data['user_id'] = $userId;

        return $telegraph;
    }

    public function chatAdministrators(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_GET_CHAT_ADMINISTRATORS;
        $telegraph->syncChatIdData();

        return $telegraph;
    }

    /**
     * @param array<int|string, string|bool> $permissions
     */
    public function setChatPermissions(array $permissions): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_SET_CHAT_PERMISSIONS;
        $telegraph->syncChatIdData();

        $permissions = collect($permissions)
            ->mapWithKeys(
                fn ($value, $key) => is_bool($value)
                    ? [$key => $value]
                    : [$value => true]
            );

        $telegraph->data['permissions'] = $permissions;

        return $telegraph;
    }

    public function banChatMember(string $userId): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_BAN_CHAT_MEMBER;
        $telegraph->syncChatIdData();
        $telegraph->data['user_id'] = $userId;

        return $telegraph;
    }

    public function until(Carbon $date): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->data['until_date'] = $date->timestamp;

        return $telegraph;
    }

    public function andRevokeMessages(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->data['revoke_messages'] = true;

        return $telegraph;
    }

    public function unbanChatMember(string $userId, bool $onlyIfBanned = true): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_UNBAN_CHAT_MEMBER;
        $telegraph->syncChatIdData();
        $telegraph->data['user_id'] = $userId;
        $telegraph->data['only_if_banned'] = $onlyIfBanned;

        return $telegraph;
    }

    /**
     * @param array<int|string, string|bool> $permissions
     */
    public function restrictChatMember(string $userId, array $permissions): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_RESTRICT_CHAT_MEMBER;
        $telegraph->syncChatIdData();
        $telegraph->data['user_id'] = $userId;

        /** @var array<string, bool> $permissions */
        $permissions = collect($permissions)
            ->mapWithKeys(
                fn ($value, $key) => is_bool($value)
                    ? [$key => $value]
                    : [$value => true]
            );


        $telegraph->data['permissions'] = $permissions;

        return $telegraph;
    }

    /**
     * @param array<int|string, string|bool> $permissions
     */
    public function promoteChatMember(string $userId, array $permissions): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_PROMOTE_CHAT_MEMBER;
        $telegraph->syncChatIdData();
        $telegraph->data['user_id'] = $userId;

        /** @var array<string, bool> $permissions */
        $permissions = collect($permissions)
            ->mapWithKeys(
                fn ($value, $key) => is_bool($value)
                    ? [$key => $value]
                    : [$value => true]
            );

        foreach ($permissions as $permission => $enabled) {
            $telegraph->data[$permission] = $enabled;
        }

        return $telegraph;
    }

    public function demoteChatMember(string $userId): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_PROMOTE_CHAT_MEMBER;
        $telegraph->syncChatIdData();
        $telegraph->data['user_id'] = $userId;

        /** @var array<string|bool> $permissions */
        $permissions = collect(ChatAdminPermissions::available_permissions())
            ->mapWithKeys(fn (string $value) => [$value => false])
            ->toArray();

        foreach ($permissions as $permission => $enabled) {
            //@phpstan-ignore-next-line
            $telegraph->data[$permission] = $enabled;
        }

        return $telegraph;
    }

    public function approveChatJoinRequest(string $userId): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_APPROVE_CHAT_JOIN_REQUEST;
        $telegraph->syncChatIdData();
        $telegraph->data['user_id'] = $userId;

        return $telegraph;
    }

    public function declineChatJoinRequest(string $userId): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_DECLINE_CHAT_JOIN_REQUEST;
        $telegraph->syncChatIdData();
        $telegraph->data['user_id'] = $userId;

        return $telegraph;
    }
}

<?php

/** @noinspection DuplicatedCode */

/** @noinspection PhpDocMissingThrowsInspection */

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\DTO\Attachment;
use DefStudio\Telegraph\Enums\ChatActions;
use DefStudio\Telegraph\Enums\ChatAdminPermissions;
use DefStudio\Telegraph\Exceptions\BotCommandException;
use DefStudio\Telegraph\Exceptions\ChatSettingsException;
use DefStudio\Telegraph\Exceptions\FileException;
use DefStudio\Telegraph\Exceptions\TelegraphException;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use DefStudio\Telegraph\Telegraph;
use File;
use Illuminate\Support\Carbon;

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

        if (empty($telegraph->chat) && $bot instanceof TelegraphBot) {
            $telegraph->chat = rescue(fn () => $telegraph->bot->chats->sole(), report: false); //@phpstan-ignore-line
        }

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
            $bot = rescue(fn () => TelegraphBot::query()->with('chats')->sole(), config('telegraph.bot_id'), false);

            $telegraph->bot = $bot;
        }

        return $telegraph->bot;
    }

    protected function getBot(): TelegraphBot|string
    {
        $telegraph = clone $this;

        return $telegraph->getBotIfAvailable() ?? throw TelegraphException::missingBot();
    }

    protected function getChatIfAvailable(): TelegraphChat|string|null
    {
        $telegraph = clone $this;

        if (empty($telegraph->chat)) {
            $bot = $telegraph->getBotIfAvailable();

            if ($bot instanceof TelegraphBot) {
                /** @var TelegraphChat|string $chat */
                $chat = rescue(fn () => $bot?->chats()->sole(), config('telegraph.chat_id'), false);

                $telegraph->chat = $chat;
            }
        }

        if (empty($telegraph->chat)) {
            /** @var TelegraphChat $chat */
            $chat = rescue(fn () => TelegraphChat::query()->sole(), null, false);

            $telegraph->chat = $chat;
        }

        return $telegraph->chat;
    }

    protected function getChat(): TelegraphChat|string
    {
        $telegraph = clone $this;

        return $telegraph->getChatIfAvailable() ?? throw TelegraphException::missingChat();
    }

    public function leaveChat(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_LEAVE_CHAT;
        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;

        return $telegraph;
    }

    public function botInfo(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_GET_BOT_INFO;

        return $telegraph;
    }

    public function botUpdates(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_GET_BOT_UPDATES;

        return $telegraph;
    }

    /**
     * @param array<string, string> $commands
     */
    public function registerBotCommands(array $commands): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_REGISTER_BOT_COMMANDS;

        if (count($commands) > 100) {
            throw BotCommandException::tooManyCommands();
        }

        $telegraph->data['commands'] = collect($commands)->map(function (string $description, string $command) {
            if (strlen($command) > 32) {
                throw BotCommandException::longCommand($command);
            }

            if (!preg_match('/[a-z0-9_]+/', $command)) {
                throw BotCommandException::invalidCommand($command);
            }

            return [
                'command' => $command,
                'description' => $description,
            ];
        })->values()->toArray();

        return $telegraph;
    }

    public function unregisterBotCommands(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_UNREGISTER_BOT_COMMANDS;

        return $telegraph;
    }

    public function chatAction(string $action): Telegraph
    {
        $telegraph = clone $this;

        in_array($action, ChatActions::available_actions()) || throw TelegraphException::invalidChatAction($action);

        $telegraph->endpoint = self::ENDPOINT_SEND_CHAT_ACTION;
        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;
        $telegraph->data['action'] = $action;

        return $telegraph;
    }

    public function setTitle(string $title): Telegraph
    {
        $telegraph = clone $this;

        !empty($title) || throw ChatSettingsException::emptyTitle();
        strlen($title) < 256 || throw ChatSettingsException::titleMaxLengthExceeded();

        $telegraph->endpoint = self::ENDPOINT_SET_CHAT_TITLE;
        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;
        $telegraph->data['title'] = $title;

        return $telegraph;
    }

    public function setDescription(string $description): Telegraph
    {
        $telegraph = clone $this;

        strlen($description) < 256 || throw ChatSettingsException::descriptionMaxLengthExceeded();

        $telegraph->endpoint = self::ENDPOINT_SET_CHAT_DESCRIPTION;
        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;
        $telegraph->data['description'] = $description;

        return $telegraph;
    }

    public function setChatPhoto(string $path): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_SET_CHAT_PHOTO;
        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;

        File::exists($path) || throw FileException::fileNotFound('photo', $path);

        if (($size = $telegraph->fileSizeInMb($path)) > Telegraph::MAX_PHOTO_SIZE_IN_MB) {
            throw FileException::photoSizeExceeded($size);
        }

        $height = $telegraph->imageHeight($path);
        $width = $telegraph->imageWidth($path);

        if (($totalLength = $height + $width) > Telegraph::MAX_PHOTO_HEIGHT_WIDTH_TOTAL) {
            throw FileException::invalidPhotoSize($totalLength);
        }

        if (($ratio = $height / $width) > Telegraph::MAX_PHOTO_HEIGHT_WIDTH_RATIO || $ratio < (1 / Telegraph::MAX_PHOTO_HEIGHT_WIDTH_RATIO)) {
            throw FileException::invalidPhotoRatio($ratio);
        }

        $telegraph->files->put('photo', new Attachment($path));

        return $telegraph;
    }

    public function deleteChatPhoto(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_DELETE_CHAT_PHOTO;
        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;

        return $telegraph;
    }

    public function chatInfo(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_GET_CHAT_INFO;
        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;

        return $telegraph;
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
        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;

        return $telegraph;
    }

    public function createChatInviteLink(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_CREATE_CHAT_INVITE_LINK;
        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;

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
        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;
        $telegraph->data['invite_link'] = $link;

        return $telegraph;
    }

    public function revokeChatInviteLink(string $link): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_REVOKE_CHAT_INVITE_LINK;
        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;
        $telegraph->data['invite_link'] = $link;

        return $telegraph;
    }

    public function chatMemberCount(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_GET_CHAT_MEMBER_COUNT;
        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;

        return $telegraph;
    }

    public function chatMember(string $userId): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_GET_CHAT_MEMBER;
        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;
        $telegraph->data['user_id'] = $userId;

        return $telegraph;
    }

    /**
     * @param array<int|string, string|bool> $permissions
     */
    public function setChatPermissions(array $permissions): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_SET_CHAT_PERMISSIONS;
        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;

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
        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;
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

    public function unbanChatMember(string $userId): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_UNBAN_CHAT_MEMBER;
        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;
        $telegraph->data['user_id'] = $userId;
        $telegraph->data['only_if_banned'] = true;

        return $telegraph;
    }

    /**
     * @param array<int|string, string|bool> $permissions
     */
    public function restrictChatMember(string $userId, array $permissions): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_RESTRICT_CHAT_MEMBER;
        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;
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
        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;
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
        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;
        $telegraph->data['user_id'] = $userId;

        $permissions = collect(ChatAdminPermissions::available_permissions())
            ->mapWithKeys(fn ($value) => [$value => false]);

        foreach ($permissions as $permission => $enabled) {
            $telegraph->data[$permission] = $enabled;
        }

        return $telegraph;
    }
}

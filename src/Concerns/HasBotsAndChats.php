<?php

/** @noinspection PhpDocMissingThrowsInspection */

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\Enums\ChatActions;
use DefStudio\Telegraph\Exceptions\BotCommandException;
use DefStudio\Telegraph\Exceptions\TelegraphException;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use DefStudio\Telegraph\Telegraph;

/**
 * @mixin Telegraph
 */
trait HasBotsAndChats
{
    private TelegraphBot|null $bot;

    private TelegraphChat|null $chat;

    public function bot(TelegraphBot $bot): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->bot = $bot;

        if (empty($telegraph->chat)) {
            $telegraph->chat = rescue(fn () => $telegraph->bot->chats->sole(), report: false); //@phpstan-ignore-line
        }

        return $telegraph;
    }

    public function chat(TelegraphChat $chat): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->chat = $chat;

        if (empty($telegraph->bot)) {
            $telegraph->bot = $telegraph->chat->bot;
        }

        return $telegraph;
    }

    protected function getBotIfAvailable(): TelegraphBot|null
    {
        $telegraph = clone $this;

        if (empty($telegraph->bot)) {
            /** @var TelegraphBot $bot */
            $bot = rescue(fn () => TelegraphBot::query()->with('chats')->sole(), null, false);

            $telegraph->bot = $bot;
        }

        return $telegraph->bot;
    }

    protected function getBot(): TelegraphBot
    {
        $telegraph = clone $this;

        return $telegraph->getBotIfAvailable() ?? throw TelegraphException::missingBot();
    }

    protected function getChatIfAvailable(): TelegraphChat|null
    {
        $telegraph = clone $this;

        if (empty($telegraph->chat)) {
            /** @var TelegraphChat $chat */
            $chat = rescue(fn () => $telegraph->getBotIfAvailable()?->chats()->sole(), null, false);

            $telegraph->chat = $chat;
        }

        if (empty($telegraph->chat)) {
            /** @var TelegraphChat $chat */
            $chat = rescue(fn () => TelegraphChat::query()->sole(), null, false);

            $telegraph->chat = $chat;
        }

        return $telegraph->chat;
    }

    protected function getChat(): TelegraphChat
    {
        $telegraph = clone $this;

        return $telegraph->getChatIfAvailable() ?? throw TelegraphException::missingChat();
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
}

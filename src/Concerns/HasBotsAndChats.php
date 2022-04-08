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
        $this->bot = $bot;

        if (empty($this->chat)) {
            $this->chat = rescue(fn () => $this->bot->chats->sole(), report: false); //@phpstan-ignore-line
        }

        return $this;
    }

    public function chat(TelegraphChat $chat): Telegraph
    {
        $self = clone $this;
        $self->chat = $chat;

        if (empty($self->bot)) {
            $self->bot = $self->chat->bot;
        }

        return $self;
    }

    protected function getBotIfAvailable(): TelegraphBot|null
    {
        if (empty($this->bot)) {
            /** @var TelegraphBot $bot */
            $bot = rescue(fn () => TelegraphBot::query()->with('chats')->sole(), null, false);

            $this->bot = $bot;
        }

        return $this->bot;
    }

    protected function getBot(): TelegraphBot
    {
        return $this->getBotIfAvailable() ?? throw TelegraphException::missingBot();
    }

    protected function getChatIfAvailable(): TelegraphChat|null
    {
        if (empty($this->chat)) {
            /** @var TelegraphChat $chat */
            $chat = rescue(fn () => $this->getBotIfAvailable()?->chats()->sole(), null, false);

            $this->chat = $chat;
        }

        if (empty($this->chat)) {
            /** @var TelegraphChat $chat */
            $chat = rescue(fn () => TelegraphChat::query()->sole(), null, false);

            $this->chat = $chat;
        }

        return $this->chat;
    }

    protected function getChat(): TelegraphChat
    {
        return $this->getChatIfAvailable() ?? throw TelegraphException::missingChat();
    }

    public function botInfo(): Telegraph
    {
        $this->endpoint = self::ENDPOINT_GET_BOT_INFO;

        return $this;
    }

    public function botUpdates(): Telegraph
    {
        $this->endpoint = self::ENDPOINT_GET_BOT_UPDATES;

        return $this;
    }

    /**
     * @param array<string, string> $commands
     */
    public function registerBotCommands(array $commands): Telegraph
    {
        $this->endpoint = self::ENDPOINT_REGISTER_BOT_COMMANDS;

        if (count($commands) > 100) {
            throw BotCommandException::tooManyCommands();
        }

        $this->data['commands'] = collect($commands)->map(function (string $description, string $command) {
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

        return $this;
    }

    public function unregisterBotCommands(): Telegraph
    {
        $this->endpoint = self::ENDPOINT_UNREGISTER_BOT_COMMANDS;

        return $this;
    }

    public function chatAction(string $action): Telegraph
    {
        in_array($action, ChatActions::available_actions()) || throw TelegraphException::invalidChatAction($action);

        $this->endpoint = self::ENDPOINT_SEND_CHAT_ACTION;
        $this->data['chat_id'] = $this->getChat()->chat_id;
        $this->data['action'] = $action;

        return $this;
    }
}

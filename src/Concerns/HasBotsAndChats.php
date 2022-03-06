<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

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
        $this->chat = $chat;

        if (empty($this->bot)) {
            $this->bot = $this->chat->bot;
        }

        return $this;
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
}

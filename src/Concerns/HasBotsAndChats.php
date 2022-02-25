<?php

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use DefStudio\Telegraph\Telegraph;

/**
 * @mixin Telegraph
 */
trait HasBotsAndChats
{
    protected TelegraphBot|null $bot;

    protected TelegraphChat|null $chat;

    protected function initBotAndChat(): void
    {
        $this->bot = rescue(fn () => TelegraphBot::query()->with('chats')->sole(), report: false); //@phpstan-ignore-line
        $this->chat = rescue(fn () => $this->bot?->chats()->sole(), report: false); //@phpstan-ignore-line
    }

    public function bot(TelegraphBot $bot): Telegraph
    {
        $this->bot = $bot;

        if (empty($this->chat)) {
            $this->chat = rescue(fn () => $this->bot->chats()->sole(), report: false); //@phpstan-ignore-line
        }

        return $this;
    }

    public function chat(TelegraphChat $chat): Telegraph
    {
        $this->chat = $chat;
        $this->bot = $this->chat->bot;

        return $this;
    }
}

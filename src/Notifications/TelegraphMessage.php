<?php

declare(strict_types=1);

namespace DefStudio\Telegraph\Notifications;

use DefStudio\Telegraph\Client\TelegraphResponse;
use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use DefStudio\Telegraph\Telegraph as TelegraphClient;

class TelegraphMessage
{
    private string $content;

    private ?string $parseMode = null;

    private TelegraphBot|string|null $bot = null;

    private TelegraphChat|string|null $chat = null;

    private ?int $threadId = null;

    private bool $silent = false;

    private bool $protectContent = false;

    private bool $withoutPreview = false;

    public function __construct(string $content = '')
    {
        $this->content = $content;
    }

    public static function make(string $content = ''): self
    {
        return new self($content);
    }

    public function content(string $content): static
    {
        $message = clone $this;

        $message->content = $content;

        return $message;
    }

    public function html(): static
    {
        $message = clone $this;

        $message->parseMode = TelegraphClient::PARSE_HTML;

        return $message;
    }

    public function markdown(): static
    {
        $message = clone $this;

        $message->parseMode = TelegraphClient::PARSE_MARKDOWN;

        return $message;
    }

    public function markdownV2(): static
    {
        $message = clone $this;

        $message->parseMode = TelegraphClient::PARSE_MARKDOWNV2;

        return $message;
    }

    public function bot(TelegraphBot|string $bot): static
    {
        $message = clone $this;

        $message->bot = $bot;

        return $message;
    }

    public function chat(TelegraphChat|string $chat): static
    {
        $message = clone $this;

        $message->chat = $chat;

        return $message;
    }

    public function inThread(int $threadId): static
    {
        $message = clone $this;

        $message->threadId = $threadId;

        return $message;
    }

    public function silent(bool $silent = true): static
    {
        $message = clone $this;

        $message->silent = $silent;

        return $message;
    }

    public function protectContent(bool $protectContent = true): static
    {
        $message = clone $this;

        $message->protectContent = $protectContent;

        return $message;
    }

    public function withoutPreview(bool $withoutPreview = true): static
    {
        $message = clone $this;

        $message->withoutPreview = $withoutPreview;

        return $message;
    }

    public function toTelegraph(mixed $route = null): TelegraphClient
    {
        /** @var TelegraphClient $telegraph */
        $telegraph = Telegraph::getFacadeRoot();
        $telegraph = TelegraphRoute::apply($telegraph, $route);

        if ($this->bot !== null) {
            $telegraph = $telegraph->bot($this->bot);
        }
        if ($this->chat !== null) {
            $telegraph = $telegraph->chat($this->chat);
        }
        if ($this->threadId !== null) {
            $telegraph = $telegraph->inThread($this->threadId);
        }

        $telegraph = match ($this->parseMode) {
            TelegraphClient::PARSE_HTML => $telegraph->html($this->content),
            TelegraphClient::PARSE_MARKDOWN => $telegraph->markdown($this->content),
            TelegraphClient::PARSE_MARKDOWNV2 => $telegraph->markdownV2($this->content),
            default => $telegraph->message($this->content),
        };

        if ($this->silent) {
            $telegraph = $telegraph->silent();
        }
        if ($this->protectContent) {
            $telegraph = $telegraph->protected();
        }
        if ($this->withoutPreview) {
            $telegraph = $telegraph->withoutPreview();
        }

        return $telegraph;
    }

    public function send(mixed $route = null): TelegraphResponse
    {
        return $this->toTelegraph($route)->send();
    }
}

<?php

/** @noinspection PhpUnused */

/** @noinspection PhpDocSignatureIsNotCompleteInspection */

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;

class TelegramUpdate implements Arrayable
{
    private int $id;
    private ?Message $message = null;
    private ?CallbackQuery $callbackQuery = null;
    private ?ChatMemberUpdate $botChatStatusChange = null;
    private ?InlineQuery $inlineQuery = null;

    private function __construct()
    {
    }

    /**
     * @param array{
     *     update_id:int,
     *     message?:array<string, mixed>,
     *     edited_message?:array<string, mixed>,
     *     channel_post?:array<string, mixed>,
     *     callback_query?:array<string, mixed>,
     *     my_chat_member?:array<string, mixed>,
     *     inline_query?:array<string, mixed>
     * } $data
     */
    public static function fromArray(array $data): TelegramUpdate
    {
        $update = new self();

        $update->id = $data['update_id'];

        if (isset($data['message'])) {
            /* @phpstan-ignore-next-line */
            $update->message = Message::fromArray($data['message']);
        }

        if (isset($data['edited_message'])) {
            /* @phpstan-ignore-next-line */
            $update->message = Message::fromArray($data['edited_message']);
        }

        if (isset($data['channel_post'])) {
            /* @phpstan-ignore-next-line */
            $update->message = Message::fromArray($data['channel_post']);
        }

        if (isset($data['callback_query'])) {
            /* @phpstan-ignore-next-line */
            $update->callbackQuery = CallbackQuery::fromArray($data['callback_query']);
        }

        if (isset($data['my_chat_member'])) {
            /* @phpstan-ignore-next-line */
            $update->botChatStatusChange = ChatMemberUpdate::fromArray($data['my_chat_member']);
        }

        if (isset($data['inline_query'])) {
            /* @phpstan-ignore-next-line */
            $update->inlineQuery = InlineQuery::fromArray($data['inline_query']);
        }

        return $update;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function message(): ?Message
    {
        return $this->message;
    }

    public function callbackQuery(): ?CallbackQuery
    {
        return $this->callbackQuery;
    }

    public function botStatusChange(): ?ChatMemberUpdate
    {
        return $this->botChatStatusChange;
    }

    public function inlineQuery(): ?InlineQuery
    {
        return $this->inlineQuery;
    }

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'message' => $this->message?->toArray(),
            'callback_query' => $this->callbackQuery?->toArray(),
            'bot_chat_status_change' => $this->botChatStatusChange?->toArray(),
            'inline_query' => $this->inlineQuery?->toArray(),
        ], fn ($value) => $value !== null);
    }
}

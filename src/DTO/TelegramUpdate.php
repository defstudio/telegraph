<?php

/** @noinspection PhpUnused */

/** @noinspection PhpDocSignatureIsNotCompleteInspection */

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, int|array<string, mixed>>
 */
class TelegramUpdate implements Arrayable
{
    private int $id;
    private ?Message $message = null;
    private ?Reaction $messageReaction = null;
    private ?Poll $poll = null;
    private ?PollAnswer $pollAnswer = null;
    private ?CallbackQuery $callbackQuery = null;
    private ?PreCheckoutQuery $preCheckoutQuery = null;

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
     *     message_reaction?:array<string, mixed>,
     *     poll?:array<string, mixed>,
     *     poll_answer?:array<string, mixed>,
     *     channel_post?:array<string, mixed>,
     *     callback_query?:array<string, mixed>,
     *     pre_checkout_query?:array<string, mixed>,
     *     my_chat_member?:array<string, mixed>,
     *     inline_query?:array<string, mixed>
     * } $data
     */
    public static function fromArray(array $data): TelegramUpdate
    {
        $update = new self();

        $update->id = $data['update_id'];

        if (isset($data['message'])) {
            $update->message = Message::fromArray($data['message']);
        }

        if (isset($data['edited_message'])) {
            $update->message = Message::fromArray($data['edited_message']);
        }

        if (isset($data['message_reaction'])) {
            $update->messageReaction = Reaction::fromArray($data['message_reaction']);
        }

        if (isset($data['poll'])) {
            $update->pollAnswer = PollAnswer::fromArray($data['poll_answer']);
        }

        if (isset($data['poll_answer'])) {
            $update->pollAnswer = PollAnswer::fromArray($data['poll_answer']);
        }

        if (isset($data['channel_post'])) {
            $update->message = Message::fromArray($data['channel_post']);
        }

        if (isset($data['callback_query'])) {
            $update->callbackQuery = CallbackQuery::fromArray($data['callback_query']);
        }

        if (isset($data['pre_checkout_query'])) {
            $update->preCheckoutQuery = PreCheckoutQuery::fromArray($data['pre_checkout_query']);
        }

        if (isset($data['my_chat_member'])) {
            $update->botChatStatusChange = ChatMemberUpdate::fromArray($data['my_chat_member']);
        }

        if (isset($data['inline_query'])) {
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

    public function messageReaction(): ?Reaction
    {
        return $this->messageReaction;
    }

    public function pollAnswer(): ?PollAnswer
    {
        return $this->pollAnswer;
    }

    public function poll(): ?Poll
    {
        return $this->poll;
    }

    public function callbackQuery(): ?CallbackQuery
    {
        return $this->callbackQuery;
    }

    public function preCheckoutQuery(): ?PreCheckoutQuery
    {
        return $this->preCheckoutQuery;
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
            'message_reaction' => $this->messageReaction?->toArray(),
            'poll' => $this->pollAnswer?->toArray(),
            'poll_answer' => $this->pollAnswer?->toArray(),
            'callback_query' => $this->callbackQuery?->toArray(),
            'pre_checkout_query' => $this->preCheckoutQuery?->toArray(),
            'bot_chat_status_change' => $this->botChatStatusChange?->toArray(),
            'inline_query' => $this->inlineQuery?->toArray(),
        ], fn ($value) => $value !== null);
    }
}

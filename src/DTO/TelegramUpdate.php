<?php

namespace DefStudio\Telegraph\DTO;

class TelegramUpdate
{
    private int $id;
    private ?Message $message = null;
    private ?CallbackQuery $callbackQuery = null;

    private function __construct(
    ) {
    }

    /**
     * @param array{update_id:int, message:array<string, mixed>, channel_post:array<string, mixed>, callback_query:array<string, mixed>} $data
     */
    public static function fromArray(array $data): TelegramUpdate
    {
        $update = new self();

        $update->id = $data['update_id'];

        if (isset($data['message'])) {
            $update->message = Message::fromArray($data['message']);
        }

        if (isset($data['channel_post'])) {
            $update->message = Message::fromArray($data['channel_post']);
        }

        if (isset($data['callback_query'])) {
            $update->callbackQuery = CallbackQuery::fromArray($data['callback_query']);
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
}

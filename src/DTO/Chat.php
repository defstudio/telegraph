<?php

/** @noinspection PhpDocSignatureIsNotCompleteInspection */

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;

class Chat implements Arrayable
{
    public const TYPE_SENDER = 'sender';
    public const TYPE_PRIVATE = 'private';
    public const TYPE_GROUP = 'group';
    public const TYPE_SUPERGROUP = 'supergroup';
    public const TYPE_CHANNEL = 'channel';

    private string $id;
    private string $type;
    private string $title;

    private function __construct()
    {
    }

    /**
     * @param array{id:string, type:string, title?:string, username?: string} $data
     */
    public static function fromArray(array $data): Chat
    {
        $chat = new self();

        $chat->id = $data['id'];
        $chat->type = $data['type'];
        $chat->title = $data['title'] ?? $data['username'] ?? '';

        return $chat;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'type' => $this->type,
            'title' => $this->title,
        ], fn ($value) => $value !== null);
    }
}

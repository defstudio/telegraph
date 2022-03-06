<?php

/** @noinspection PhpDocSignatureIsNotCompleteInspection */

namespace DefStudio\Telegraph\DTO;

use phpDocumentor\Reflection\Type;

class Chat
{
    public const TYPE_PRIVATE = 'private';
    public const TYPE_GROUP = 'group';
    public const TYPE_SUPERGROUP = 'supergroup';
    public const TYPE_CHANNEL = 'channel';

    private int $id;
    private string $type;
    private string $title;

    private function __construct()
    {
    }

    /**
     * @param array{id:int, type:string, title?:string} $data
     */
    public static function fromArray(array $data): Chat
    {
        $chat = new self();

        $chat->id = $data['id'];
        $chat->type = $data['type'];
        $chat->title = $data['title'] ?? '';

        return $chat;
    }

    public function id(): int
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
}

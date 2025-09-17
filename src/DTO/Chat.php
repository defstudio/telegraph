<?php

/** @noinspection PhpDocSignatureIsNotCompleteInspection */

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, string>
 */
class Chat implements Arrayable
{
    public const TYPE_SENDER = 'sender';
    public const TYPE_PRIVATE = 'private';
    public const TYPE_GROUP = 'group';
    public const TYPE_SUPERGROUP = 'supergroup';
    public const TYPE_CHANNEL = 'channel';

    private string $id;
    private string $type;
    private ?string $title;
    private ?string $username;
    private ?string $firstName;
    private ?string $lastName;
    private bool $isForum = false;
    private bool $isDirectMessages = false;

    private function __construct()
    {
    }

    /**
     * @param array{
     *     id:int,
     *     type:string,
     *     title?:string,
     *     username?:string,
     *     first_name?:string,
     *     last_name?:string,
     *     is_forum?:bool,
     *     is_direct_messages?:bool,
     *  } $data
     */
    public static function fromArray(array $data): Chat
    {
        $chat = new self();

        $chat->id = $data['id'];
        $chat->type = $data['type'];
        $chat->title = $data['title'] ?? null;
        $chat->username = $data['username'] ?? null;
        $chat->firstName = $data['first_name'] ?? null;
        $chat->lastName = $data['last_name'] ?? null;
        $chat->isForum = $data['is_forum'] ?? false;
        $chat->isDirectMessages = $data['is_direct_messages'] ?? false;

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

    public function title(): ?string
    {
        return $this->title;
    }

    public function username(): ?string
    {
        return $this->username;
    }

    public function firstName(): ?string
    {
        return $this->firstName;
    }

    public function lastName(): ?string
    {
        return $this->lastName;
    }

    public function isForum(): bool
    {
        return $this->isForum;
    }

    public function isDirectMessages(): bool
    {
        return $this->isDirectMessages;
    }

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'type' => $this->type,
            'title' => $this->title,
            'username' => $this->username,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'is_forum' => $this->isForum,
            'is_direct_messages' => $this->isDirectMessages,
        ], fn ($value) => $value !== null);
    }
}

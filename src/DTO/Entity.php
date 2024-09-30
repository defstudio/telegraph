<?php

declare(strict_types=1);

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;

class Entity implements Arrayable
{
    private string $type;

    private int $offset;

    private int $length;

    private ?string $url = null;

    private ?User $user = null;

    private ?string $language = null;

    private ?string $customEmojiId = null;

    private function __construct()
    {
    }

    public static function fromArray(array $data): Entity
    {
        $entity = new self();

        $entity->type = $data['type'];
        $entity->offset = $data['offset'];
        $entity->length = $data['length'];

        if (isset($data['url'])) {
            $entity->url = $data['url'];
        }

        if (isset($data['user'])) {
            $entity->user = User::fromArray($data['user']);
        }

        if (isset($data['language'])) {
            $entity->language = $data['language'];
        }

        if (isset($data['custom_emoji_id'])) {
            $entity->customEmojiId = $data['custom_emoji_id'];
        }

        return $entity;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function offset(): int
    {
        return $this->offset;
    }

    public function length(): int
    {
        return $this->length;
    }

    public function url(): ?string
    {
        return $this->url;
    }

    public function user(): ?User
    {
        return $this->user;
    }

    public function language(): ?string
    {
        return $this->language;
    }

    public function customEmojiId(): ?string
    {
        return $this->customEmojiId;
    }

    public function toArray(): array
    {
        return array_filter([
            'type' => $this->type,
            'offset' => $this->offset,
            'length' => $this->length,
            'url' => $this->url,
            'user' => $this->user()?->toArray(),
            'language' => $this->language,
            'custom_emoji_id' => $this->customEmojiId,
        ], fn ($value) => $value !== null);
    }
}

<?php

namespace DefStudio\Telegraph\DTO;

use Illuminate\Contracts\Support\Arrayable;

class Reaction implements Arrayable
{
    private string $type;
    private ?string $emoji = null;
    private ?string $customEmojiId = null;

    private function __construct()
    {
    }

    /**
     * @param array{type: string, emoji?: string, custom_emoji_id?: string} $data
     */
    public static function fromArray(array $data): Reaction
    {
        $reaction = new self();

        $reaction->type = $data['type'];
        $reaction->emoji = $data['emoji'] ?? null;
        $reaction->customEmojiId = $data['custom_emoji_id'] ?? null;

        return $reaction;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function emoji(): ?string
    {
        return $this->emoji;
    }

    public function customEmojiId(): ?string
    {
        return $this->customEmojiId;
    }

    public function toArray(): array
    {
        return array_filter([
            'type' => $this->type,
            'emoji' => $this->emoji,
            'custom_emoji_id' => $this->customEmojiId,
        ], fn ($value) => $value !== null);
    }
}

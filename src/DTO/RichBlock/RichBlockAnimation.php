<?php

namespace DefStudio\Telegraph\DTO\RichBlock;

use DefStudio\Telegraph\Contracts\RichBlockItem;
use DefStudio\Telegraph\DTO\Animation;
use DefStudio\Telegraph\DTO\RichBlock\RichBlockElements\RichBlockCaption;
use DefStudio\Telegraph\Exceptions\RichBlockException;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, string|int>
 */
class RichBlockAnimation implements RichBlockItem, Arrayable
{
    private const TYPE = 'animation';
    private Animation $animation;
    private bool $hasSpoiler = false;
    private ?RichBlockCaption $caption = null;

    /**
     * @param  array{
     *     type:string,
     *     animation:array<string, mixed>,
     *     has_spoiler?: bool,
     *     caption?: array<string, mixed>
     * }  $data
     *
     * @return RichBlockAnimation
     */
    public static function fromArray(array $data): RichBlockAnimation
    {
        if ($data['type'] !== self::TYPE) {
            throw RichBlockException::structureMismatch();
        }

        $richBlockAnimation = new self();

        $richBlockAnimation->animation = Animation::fromArray($data['animation']);

        $richBlockAnimation->hasSpoiler = $data['has_spoiler'] ?? false;

        if (isset($data['caption']) && $data['caption']) {
            $richBlockAnimation->caption = RichBlockCaption::fromArray($data['caption']);
        }

        return $richBlockAnimation;
    }

    public function type(): string
    {
        return self::TYPE;
    }

    public function animation(): Animation
    {
        return $this->animation;
    }

    public function hasSpoiler(): bool
    {
        return $this->hasSpoiler;
    }

    public function caption(): ?RichBlockCaption
    {
        return $this->caption;
    }

    public function toArray(): array
    {
        return array_filter([
            'type' => self::TYPE,
            'animation' => $this->animation->toArray(),
            'has_spoiler' => $this->hasSpoiler ? true : null,
            'caption' => $this->caption?->toArray(),
        ], fn ($value) => $value !== null);
    }
}

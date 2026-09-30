<?php

namespace DefStudio\Telegraph\DTO\RichBlock;

use DefStudio\Telegraph\Contracts\RichBlockItem;
use DefStudio\Telegraph\DTO\Factories\RichBlockFactory;
use DefStudio\Telegraph\DTO\RichBlock\RichBlockElements\RichBlockCaption;
use DefStudio\Telegraph\Exceptions\RichBlockException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

/**
 * @implements Arrayable<string, string|int>
 */
class RichBlockSlideshow implements RichBlockItem, Arrayable
{
    private const TYPE = 'slideshow';
    /** @var Collection<array-key,RichBlockItem> */
    private Collection $blocks;
    private ?RichBlockCaption $caption = null;

    /**
     * @param  array{
     *     type: string,
     *     blocks: array<array-key,Object>,
     *     caption?: array<string,mixed>
     * }  $data
     *
     * @return RichBlockSlideshow
     */
    public static function fromArray(array $data): RichBlockSlideshow
    {
        if ($data['type'] !== self::TYPE) {
            throw RichBlockException::structureMismatch();
        }

        $richBlockSlideshow = new self();

        /* @phpstan-ignore-next-line */
        $richBlockSlideshow->blocks = collect($data['blocks'])->map(fn (array $blockData) => app(RichBlockFactory::class)->fromArray($blockData));

        if (isset($data['caption']) && $data['caption']) {
            /* @phpstan-ignore-next-line */
            $richBlockSlideshow->caption = RichBlockCaption::fromArray($data['caption']);
        }

        return $richBlockSlideshow;
    }

    public function type(): string
    {
        return self::TYPE;
    }

    /**
     * @return Collection<array-key, RichBlockItem>
     */
    public function blocks(): Collection
    {
        return $this->blocks;
    }

    public function caption(): ?RichBlockCaption
    {
        return $this->caption;
    }

    public function toArray(): array
    {
        return array_filter([
            'type' => self::TYPE,
            'blocks' => $this->blocks->toArray(),
            'caption' => $this->caption?->toArray(),
        ], fn ($value) => $value !== null);
    }
}

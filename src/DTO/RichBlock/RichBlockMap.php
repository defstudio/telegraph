<?php

namespace DefStudio\Telegraph\DTO\RichBlock;

use DefStudio\Telegraph\Contracts\RichBlockItem;
use DefStudio\Telegraph\DTO\Location;
use DefStudio\Telegraph\DTO\RichBlock\RichBlockElements\RichBlockCaption;
use DefStudio\Telegraph\Exceptions\RichBlockException;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, string|int>
 */
class RichBlockMap implements RichBlockItem, Arrayable
{
    private const TYPE = 'map';
    private Location $location;
    private int $zoom;
    private int $width;
    private int $height;
    private ?RichBlockCaption $caption = null;

    /**
     * @param  array{
     *     type: string,
     *     location: array<string, mixed>,
     *     zoom: int,
     *     width: int,
     *     height: int,
     *     caption?: array<string, mixed>
     * }  $data
     *
     * @return RichBlockMap
     */
    public static function fromArray(array $data): RichBlockMap
    {
        if ($data['type'] !== self::TYPE) {
            throw RichBlockException::structureMismatch();
        }

        $richBlockMap = new self();

        $richBlockMap->location = Location::fromArray($data['location']);

        $richBlockMap->zoom = $data['zoom'];
        $richBlockMap->width = $data['width'];
        $richBlockMap->height = $data['height'];

        if (isset($data['caption']) && $data['caption']) {
            $richBlockMap->caption = RichBlockCaption::fromArray($data['caption']);
        }

        return $richBlockMap;
    }

    public function type(): string
    {
        return self::TYPE;
    }

    public function location(): Location
    {
        return $this->location;
    }

    public function zoom(): int
    {
        return $this->zoom;
    }

    public function width(): int
    {
        return $this->width;
    }

    public function height(): int
    {
        return $this->height;
    }

    public function caption(): ?RichBlockCaption
    {
        return $this->caption;
    }

    public function toArray(): array
    {
        return array_filter([
            'type' => self::TYPE,
            'location' => $this->location->toArray(),
            'zoom' => $this->zoom,
            'width' => $this->width,
            'height' => $this->height,
            'caption' => $this->caption?->toArray(),
        ], fn ($value) => $value !== null);
    }
}

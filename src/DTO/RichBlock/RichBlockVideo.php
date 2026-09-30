<?php

namespace DefStudio\Telegraph\DTO\RichBlock;

use DefStudio\Telegraph\Contracts\RichBlockItem;
use DefStudio\Telegraph\DTO\RichBlock\RichBlockElements\RichBlockCaption;
use DefStudio\Telegraph\DTO\Video;
use DefStudio\Telegraph\Exceptions\RichBlockException;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, string|int>
 */
class RichBlockVideo implements RichBlockItem, Arrayable
{
    private const TYPE = 'video';
    private Video $video;
    private bool $hasSpoiler = false;
    private ?RichBlockCaption $caption = null;

    /**
     * @param  array{
     *     type:string,
     *     video:array<string, mixed>,
     *     has_spoiler?: bool,
     *     caption?: array<string, mixed>
     * }  $data
     *
     * @return RichBlockVideo
     */
    public static function fromArray(array $data): RichBlockVideo
    {
        if ($data['type'] !== self::TYPE) {
            throw RichBlockException::structureMismatch();
        }

        $richBlockVideo = new self();

        $richBlockVideo->video = Video::fromArray($data['video']);

        $richBlockVideo->hasSpoiler = $data['has_spoiler'] ?? false;

        if (isset($data['caption']) && $data['caption']) {
            $richBlockVideo->caption = RichBlockCaption::fromArray($data['caption']);
        }

        return $richBlockVideo;
    }

    public function type(): string
    {
        return self::TYPE;
    }

    public function video(): Video
    {
        return $this->video;
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
            'video' => $this->video->toArray(),
            'has_spoiler' => $this->hasSpoiler ? true : null,
            'caption' => $this->caption?->toArray(),
        ], fn ($value) => $value !== null);
    }
}

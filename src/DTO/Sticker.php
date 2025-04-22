<?php

namespace DefStudio\Telegraph\DTO;

use DefStudio\Telegraph\Contracts\Downloadable;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, string|int|bool|array<string, mixed>>
 */
class Sticker implements Arrayable, Downloadable
{
    private string $id;
    private string $type;
    private int $width;
    private int $height;
    private bool $isAnimated = false;
    private bool $isVideo = false;
    private ?string $emoji = null ;
    private ?int $filesize = null;
    private ?Photo $thumbnail = null;

    private function __construct()
    {
    }

    /**
     * @param array{
     *     file_id: string,
     *     width: int,
     *     height: int,
     *     type: string,
     *     is_animated: bool,
     *     is_video: bool,
     *     file_size?: int,
     *     emoji?: string,
     *     thumb?: array<string, mixed>,
     * } $data
     */
    public static function fromArray(array $data): Sticker
    {
        $sticker = new self();

        $sticker->id = $data['file_id'];
        $sticker->width = $data['width'];
        $sticker->height = $data['height'];
        $sticker->type = $data['type'];
        $sticker->isAnimated = $data['is_animated'];
        $sticker->isVideo = $data['is_video'];
        $sticker->emoji = $data['emoji'] ?? null;
        $sticker->filesize = $data['file_size'] ?? null;

        if (isset($data['thumb'])) {
            $sticker->thumbnail = Photo::fromArray($data['thumb']);
        }

        return $sticker;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function width(): int
    {
        return $this->width;
    }

    public function height(): int
    {
        return $this->height;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function isAnimated(): bool
    {
        return $this->isAnimated;
    }

    public function isVideo(): bool
    {
        return $this->isVideo;
    }

    public function emoji(): ?string
    {
        return $this->emoji;
    }

    public function filesize(): ?int
    {
        return $this->filesize;
    }

    public function thumbnail(): ?Photo
    {
        return $this->thumbnail;
    }

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'width' => $this->width,
            'height' => $this->height,
            'type' => $this->type,
            'is_animated' => $this->isAnimated,
            'is_video' => $this->isVideo,
            'filesize' => $this->filesize,
            'emoji' => $this->emoji,
            'thumbnail' => $this->thumbnail?->toArray(),
        ], fn ($value) => $value !== null);
    }
}

<?php

namespace DefStudio\Telegraph\DTO;

use DefStudio\Telegraph\Contracts\Downloadable;
use Illuminate\Contracts\Support\Arrayable;

class Animation implements Arrayable, Downloadable
{
    private string $id;

    private int $width;
    private int $height;
    private ?int $duration = null;
    private ?int $filesize = null;

    private ?string $filename = null;
    private ?string $mimeType = null;

    private ?Photo $thumbnail = null;

    private function __construct()
    {
    }

    /**
     * @param array{
     *     file_id: string,
     *      width: int,
     *      height: int,
     *     duration: int,
     *     file_name?: string,
     *     mime_type?: string,
     *     file_size?: int,
     *     thumb?: array<string, mixed>,
     * } $data
     */
    public static function fromArray(array $data): Animation
    {
        $animation = new self();

        $animation->id = $data['file_id'];
        $animation->width = $data['width'];
        $animation->height = $data['height'];
        $animation->duration = $data['duration'];

        $animation->filesize = $data['file_size'] ?? null;
        $animation->filename = $data['file_name'] ?? null;
        $animation->mimeType = $data['mime_type'] ?? null;

        if (isset($data['thumb'])) {
            /* @phpstan-ignore-next-line */
            $animation->thumbnail = Photo::fromArray($data['thumb']);
        }

        return $animation;
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

    public function duration(): ?int
    {
        return $this->duration;
    }

    public function filesize(): ?int
    {
        return $this->filesize;
    }

    public function filename(): ?string
    {
        return $this->filename;
    }

    public function mimeType(): ?string
    {
        return $this->mimeType;
    }

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'width' => $this->width,
            'height' => $this->height,
            'duration' => $this->duration,
            'filename' => $this->filename,
            'mime_type' => $this->mimeType,
            'filesize' => $this->filesize,
            'thumbnail' => $this->thumbnail?->toArray(),
        ], fn ($value) => $value !== null);
    }
}

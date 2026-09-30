<?php

namespace DefStudio\Telegraph\DTO;

use DefStudio\Telegraph\Contracts\Downloadable;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, string|int|array<string, mixed>>
 */
class VideoNote implements Arrayable, Downloadable
{
    private string $id;
    private int $duration;
    private int $length;
    private ?int $filesize = null;

    private ?Photo $thumbnail = null;

    private function __construct()
    {
    }

    /**
     * @param  array{
     *     file_id: string,
     *     length: int,
     *     duration: int,
     *     file_size?: int,
     *     thumb?: array<string, mixed>,
     * }  $data
     */
    public static function fromArray(array $data): VideoNote
    {
        $video = new self();

        $video->id = $data['file_id'];
        $video->length = $data['length'];
        $video->duration = $data['duration'];
        $video->filesize = $data['file_size'] ?? null;

        if (isset($data['thumb'])) {
            $video->thumbnail = Photo::fromArray($data['thumb']);
        }

        return $video;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function length(): int
    {
        return $this->length;
    }

    public function duration(): int
    {
        return $this->duration;
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
            'length' => $this->length,
            'duration' => $this->duration,
            'filesize' => $this->filesize,
            'thumbnail' => $this->thumbnail?->toArray(),
        ], fn ($value) => $value !== null);
    }
}

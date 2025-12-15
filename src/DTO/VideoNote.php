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
    private ?int $length = null;

    private ?string $filename = null;
    private ?string $mimeType = null;
    private ?int $filesize = null;

    private ?Photo $thumbnail = null;

    private function __construct()
    {
    }


    /**
     * @param array{
     *     file_id: string,
     *     duration: int,
     *     length?: int,
     *     file_size?: int,
     *     thumb?: array<string, mixed>,
     * } $data
     */
    public static function fromArray(array $data): VideoNote
    {
        $videoNote = new self();

        $videoNote->id = $data['file_id'];
        $videoNote->duration = $data['duration'];
        $videoNote->length = $data['length'] ?? null;
        $videoNote->filesize = $data['file_size'] ?? null;

        if (isset($data['thumb'])) {
            $videoNote->thumbnail = Photo::fromArray($data['thumb']);
        }

        return $videoNote;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function duration(): int
    {
        return $this->duration;
    }
    public function length(): ?int
    {
        return $this->length;
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
            'duration' => $this->duration,
            'filename' => $this->filename,
            'mime_type' => $this->mimeType,
            'filesize' => $this->filesize,
            'thumbnail' => $this->thumbnail?->toArray(),
        ], fn ($value) => $value !== null);
    }
}

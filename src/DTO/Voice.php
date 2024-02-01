<?php

namespace DefStudio\Telegraph\DTO;

use DefStudio\Telegraph\Contracts\Downloadable;
use Illuminate\Contracts\Support\Arrayable;

class Voice implements Arrayable, Downloadable
{
    private string $id;
    private int $duration;

    private ?string $mimeType = null;
    private ?int $filesize = null;

    private function __construct()
    {
    }

    /**
     * @param array{
     *     file_id: string,
     *     duration: int,
     *     mime_type?: string,
     *     file_size?: int,
     * } $data
     */
    public static function fromArray(array $data): Voice
    {
        $voice = new self();

        $voice->id = $data['file_id'];
        $voice->duration = $data['duration'];

        $voice->mimeType = $data['mime_type'] ?? null;
        $voice->filesize = $data['file_size'] ?? null;

        return $voice;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function duration(): int
    {
        return $this->duration;
    }

    public function mimeType(): ?string
    {
        return $this->mimeType;
    }

    public function filesize(): ?int
    {
        return $this->filesize;
    }

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'duration' => $this->duration,
            'mime_type' => $this->mimeType,
            'filesize' => $this->filesize,
        ], fn ($value) => $value !== null);
    }
}

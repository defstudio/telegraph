<?php

namespace DefStudio\Telegraph\DTO;

use DefStudio\Telegraph\Contracts\Downloadable;
use Illuminate\Contracts\Support\Arrayable;

class Voice implements Arrayable, Downloadable
{
    private string $id;
    private int $duration;

    private ?string $title = null;
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
     *     title?: string,
     *     file_name?: string,
     *     mime_type?: string,
     *     file_size?: int,
     *     thumb?: array<string, mixed>,
     * } $data
     */
    public static function fromArray(array $data): Voice
    {
        $voice = new self();

        $voice->id = $data['file_id'];
        $voice->duration = $data['duration'];

        $voice->title = $data['title'] ?? null;
        $voice->filename = $data['file_name'] ?? null;
        $voice->mimeType = $data['mime_type'] ?? null;
        $voice->filesize = $data['file_size'] ?? null;

        if (isset($data['thumb'])) {
            /* @phpstan-ignore-next-line  */
            $voice->thumbnail = Photo::fromArray($data['thumb']);
        }

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

    public function title(): ?string
    {
        return $this->title;
    }

    public function filename(): ?string
    {
        return $this->filename;
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
            'title' => $this->title,
            'filename' => $this->filename,
            'mime_type' => $this->mimeType,
            'filesize' => $this->filesize,
            'thumbnail' => $this->thumbnail?->toArray(),
        ]);
    }
}

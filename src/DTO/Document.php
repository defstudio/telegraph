<?php

namespace DefStudio\Telegraph\DTO;

use DefStudio\Telegraph\Contracts\Downloadable;
use Illuminate\Contracts\Support\Arrayable;

class Document implements Arrayable, Downloadable
{
    private string $id;

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
     *     file_name?: string,
     *     mime_type?: string,
     *     file_size?: int,
     *     thumb?: array<string, mixed>,
     * } $data
     */
    public static function fromArray(array $data): Document
    {
        $document = new self();

        $document->id = $data['file_id'];
        $document->filename = $data['file_name'] ?? null;
        $document->mimeType = $data['mime_type'] ?? null;
        $document->filesize = $data['file_size'] ?? null;

        if (isset($data['thumb'])) {
            /* @phpstan-ignore-next-line  */
            $document->thumbnail = Photo::fromArray($data['thumb']);
        }

        return $document;
    }

    public function id(): string
    {
        return $this->id;
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
            'filename' => $this->filename,
            'mime_type' => $this->mimeType,
            'filesize' => $this->filesize,
            'thumbnail' => $this->thumbnail?->toArray(),
        ], fn ($value) => $value !== null);
    }
}

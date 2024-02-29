<?php

namespace DefStudio\Telegraph\DTO;

use DefStudio\Telegraph\Contracts\Downloadable;
use Illuminate\Contracts\Support\Arrayable;

class Photo implements Arrayable, Downloadable
{
    private string $id;
    private int $width;
    private int $height;
    private ?int $filesize = null;

    private function __construct()
    {
    }

    /**
     * @param array{
     *     file_id: string,
     *     width: int,
     *     height: int,
     *     file_size?: int
     * } $data
     */
    public static function fromArray(array $data): Photo
    {
        $photo = new self();

        $photo->id = $data['file_id'];
        $photo->width = $data['width'];
        $photo->height = $data['height'];
        $photo->filesize = $data['file_size'] ?? null;

        return $photo;
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

    public function filesize(): ?int
    {
        return $this->filesize;
    }

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'width' => $this->width,
            'height' => $this->height,
            'filesize' => $this->filesize,
        ], fn ($value) => $value !== null);
    }
}

<?php

namespace DefStudio\Telegraph\DTO;

use GuzzleHttp\Psr7\Utils;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Attachment implements Arrayable
{
    public function __construct(
        private string $path,
        private string|null $filename = null,
    ) {
    }

    public function path(): string
    {
        return $this->path;
    }

    public function local(): bool
    {
        return Str::of($this->path)->startsWith('/');
    }

    public function contents(): string
    {
        if ($this->local()) {
            return File::get($this->path);
        }

        return (string) Utils::streamFor($this->path);
    }

    public function filename(): string
    {
        return $this->filename ?? File::basename($this->path);
    }

    public function toArray(): array
    {
        return [
            'contents' => $this->contents(),
            'filename' => $this->filename(),
        ];
    }
}

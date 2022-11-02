<?php

namespace DefStudio\Telegraph\DTO;

use Illuminate\Support\Str;

abstract class InputMedia
{
    protected string $type;
    protected string $path;
    protected ?string $filename;
    protected string $attachName;

    public function getAttachName(): string
    {
        return $this->attachName;
    }

    protected function attachString(): string
    {
        return 'attach://' . $this->getAttachName();
    }

    protected function local(): bool
    {
        return Str::of($this->path)->startsWith('/');
    }

    protected function remote(): bool
    {
        return (bool) filter_var($this->path, FILTER_VALIDATE_URL);
    }

    abstract public function asMultipart(): bool;

    abstract public function toAttachment(): Attachment;

    /**
     * @return array<string, string>
     */
    abstract public function toMediaArray(): array;

    abstract protected function validate(): void;

    protected function generateRandomName(): string
    {
        return substr(md5(uniqid((string) $this->filename, true)), 0, 10);
    }
}

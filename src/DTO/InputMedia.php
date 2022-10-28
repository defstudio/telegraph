<?php

namespace DefStudio\Telegraph\DTO;

abstract class InputMedia
{
    protected string $type;

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

    abstract public function local(): bool;

    abstract public function toAttachment(): Attachment;

    abstract public function toMediaArray(): array;

    abstract protected function validate(): void;

    protected function generateRandomName(): string
    {
        return substr(md5(uniqid((string) $this->filename, true)), 0, 10);
    }
}

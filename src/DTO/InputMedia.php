<?php

namespace DefStudio\Telegraph\DTO;

use Psr\Http\Message\StreamInterface;

abstract class InputMedia
{
    protected string $type;
    protected Attachment $attachment;

    public function __construct(
        protected string|StreamInterface $contents,
        ?string $filename = null,
        protected ?string $caption = null,
        protected ?string $parseMode = null,
        bool $preload = false,
    ) {
        $this->validate();

        $this->type = 'photo';
        $this->attachment = new Attachment($this->contents, $filename, $preload);
    }

    public function attachment(): Attachment
    {
        return $this->attachment;
    }

    public function asMultipart(): bool
    {
        return $this->attachment()->asMultipart();
    }

    /**
     * @return array<string, string>
     */
    abstract public function toMediaArray(): array;

    abstract protected function validate(): void;
}

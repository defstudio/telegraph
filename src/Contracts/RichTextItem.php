<?php

namespace DefStudio\Telegraph\Contracts;

interface RichTextItem
{
    /**
     * @param  string|array<string,mixed>  $data
     *
     * @return self
     */
    public static function fromData(string|array $data): self;

    public function type(): ?string;

    /**
     * @return array<string,mixed>|string
     */
    public function build(): array|string;
}

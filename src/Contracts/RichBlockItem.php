<?php

namespace DefStudio\Telegraph\Contracts;

interface RichBlockItem
{
    /**
     * @param  array<string, mixed>  $data
     *
     * @return self
     */
    public static function fromArray(array $data): self;

    public function type(): string;
}

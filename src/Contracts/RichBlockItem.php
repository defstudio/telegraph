<?php

namespace DefStudio\Telegraph\Contracts;

interface RichBlockItem
{
    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self;

    public function type(): string;
}

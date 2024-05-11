<?php

declare(strict_types=1);

namespace DefStudio\Telegraph\Contracts;

interface ShouldCreatesFromArray
{
    public static function fromArray(array $data): static;
}

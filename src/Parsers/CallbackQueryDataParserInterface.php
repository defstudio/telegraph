<?php

namespace DefStudio\Telegraph\Parsers;

use Illuminate\Support\Collection;

interface CallbackQueryDataParserInterface
{
    public function parse(string $rawData): Collection;

    /**
     * @param array<string, string> $data
     */
    public function encode(array $data): string;
}

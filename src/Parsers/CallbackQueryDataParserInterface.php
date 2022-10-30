<?php

namespace DefStudio\Telegraph\Parsers;

use Illuminate\Support\Collection;

interface CallbackQueryDataParserInterface
{
    public function parse(string $rawData): Collection;

    public function encode(array $data): string;
}

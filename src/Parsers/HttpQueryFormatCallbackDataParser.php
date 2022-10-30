<?php

namespace DefStudio\Telegraph\Parsers;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * callback_data format: `action?param=1&...`
 */
class HttpQueryFormatCallbackDataParser implements CallbackQueryDataParserInterface
{
    public function parse(string $rawData): Collection
    {
        $exploded = explode('?', $rawData);
        if (count($exploded) !== 2) {
            return Collection::empty();
        }

        [$name, $data] = $exploded;
        parse_str($data, $decoded);

        return Collection::make($decoded)->put('action', $name);
    }

    public function encode(array $data): string
    {
        $action = Arr::get($data, 'action');
        $data = Arr::except($data, 'action');

        return mb_convert_encoding(sprintf("%s?%s", $action, http_build_query($data)), "ASCII");
    }
}

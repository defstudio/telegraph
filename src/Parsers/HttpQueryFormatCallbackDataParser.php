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
        $callbackData = Collection::empty();
        $exploded = explode('?', $rawData);
        if (count($exploded) !== 2) {
            return $callbackData;
        }

        [$name, $data] = $exploded;
        parse_str($data, $decoded);

        return $callbackData
            ->merge($decoded)
            ->put('action', $name);
    }

    /**
     * @param array<string, string> $data
     */
    public function encode(array $data): string
    {
        /** @var string $action */
        $action = Arr::get($data, 'action');
        $data = Arr::except($data, 'action');
        $query = http_build_query($data);

        return mb_convert_encoding(sprintf("%s?%s", $action, $query), "ASCII");
    }
}

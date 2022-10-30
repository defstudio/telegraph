<?php

namespace DefStudio\Telegraph\Parsers;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * callback_data format: `action:name;param:1;param2:...`
 */
class DefaultCallbackQueryDataParser implements CallbackQueryDataParserInterface
{
    public function parse(string $rawData): Collection
    {
        return Str::of($data['data'] ?? '')
            ->explode(';')
            ->filter()
            /* @phpstan-ignore-next-line */
            ->mapWithKeys(function (string $entity) {
                $entity = explode(':', $entity);
                $key = $entity[0];
                $value = $entity[1];

                return [$key => $value];
            });
    }

    public function encode(array $data): string
    {
        return implode(';', $data);
    }
}

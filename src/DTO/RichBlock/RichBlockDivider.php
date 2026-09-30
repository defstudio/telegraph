<?php

namespace DefStudio\Telegraph\DTO\RichBlock;

use DefStudio\Telegraph\Contracts\RichBlockItem;
use DefStudio\Telegraph\Exceptions\RichBlockException;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, string|int>
 */
class RichBlockDivider implements RichBlockItem, Arrayable
{
    private const TYPE = 'divider';

    /**
     * @param  array{
     *     type: string
     * }  $data
     *
     * @return RichBlockDivider
     * @throws RichBlockException
     */
    public static function fromArray(array $data): RichBlockDivider
    {
        if ($data['type'] !== self::TYPE) {
            throw RichBlockException::structureMismatch();
        }

        return new self();
    }

    public function type(): string
    {
        return self::TYPE;
    }

    public function toArray(): array
    {
        return array_filter([
            'type' => self::TYPE,
        ], fn ($value) => $value !== null); //@phpstan-ignore-line
    }
}

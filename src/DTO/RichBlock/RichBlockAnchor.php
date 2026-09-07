<?php

namespace DefStudio\Telegraph\DTO\RichBlock;

use DefStudio\Telegraph\Contracts\RichBlockItem;
use DefStudio\Telegraph\Exceptions\RichBlockException;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, string|int>
 */
class RichBlockAnchor implements RichBlockItem, Arrayable
{
    private const TYPE = 'anchor';
    private string $name;

    /**
     * @param  array{
     *     type: string,
     *     name: string,
     * }  $data
     *
     * @return RichBlockAnchor
     */
    public static function fromArray(array $data): RichBlockAnchor
    {
        if ($data['type'] !== self::TYPE) {
            throw RichBlockException::structureMismatch();
        }

        $richBlockAnchor = new self();

        $richBlockAnchor->name = $data['name'];

        return $richBlockAnchor;
    }

    public function type(): string
    {
        return self::TYPE;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function toArray(): array
    {
        return array_filter([
            'type' => self::TYPE,
            'name' => $this->name,
        ], fn ($value) => $value !== null); //@phpstan-ignore-line
    }
}

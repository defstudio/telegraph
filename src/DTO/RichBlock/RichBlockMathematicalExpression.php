<?php

namespace DefStudio\Telegraph\DTO\RichBlock;

use DefStudio\Telegraph\Contracts\RichBlockItem;
use DefStudio\Telegraph\Exceptions\RichBlockException;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, string|int>
 */
class RichBlockMathematicalExpression implements RichBlockItem, Arrayable
{
    private const TYPE = 'mathematical_expression';
    private string $expression;

    /**
     * @param  array{
     *     type: string,
     *     expression: string,
     * }  $data
     *
     * @return RichBlockMathematicalExpression
     */
    public static function fromArray(array $data): RichBlockMathematicalExpression
    {
        if ($data['type'] !== self::TYPE) {
            throw RichBlockException::structureMismatch();
        }

        $richBlockMathematicalExpression = new self();

        $richBlockMathematicalExpression->expression = $data['expression'];

        return $richBlockMathematicalExpression;
    }

    public function type(): string
    {
        return self::TYPE;
    }

    public function expression(): string
    {
        return $this->expression;
    }

    public function toArray(): array
    {
        return array_filter([
            'type' => self::TYPE,
            'expression' => $this->expression,
        ], fn ($value) => $value !== null); //@phpstan-ignore-line
    }
}

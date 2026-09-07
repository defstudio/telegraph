<?php

namespace DefStudio\Telegraph\DTO\RichText;

use DefStudio\Telegraph\Contracts\RichTextItem;
use DefStudio\Telegraph\Exceptions\RichTextException;

class RichTextMathematicalExpression implements RichTextItem
{
    private const TYPE = 'mathematical_expression';

    private string $expression;

    /**
     * @param  string|array{
     *     type: string,
     *     expression: string,
     * }  $data
     *
     * @return RichTextMathematicalExpression
     */
    public static function fromData(string|array $data): RichTextMathematicalExpression
    {
        $richTextMathematicalExpression = new self();

        if (!is_array($data) || $data['type'] !== self::TYPE) {
            throw RichTextException::structureMismatch();
        }

        $richTextMathematicalExpression->expression = $data['expression'];

        return $richTextMathematicalExpression;
    }

    public function type(): ?string
    {
        return self::TYPE;
    }

    public function expression(): string
    {
        return $this->expression;
    }

    public function build(): array|string
    {
        return array_filter([
            'type' => self::TYPE,
            'expression' => $this->expression,
        ], fn ($value) => $value !== null); //@phpstan-ignore-line
    }
}

<?php

namespace DefStudio\Telegraph\Keyboard;

class Button
{
    private string $url;

    /** @var string[] */
    private array $callbackData = [];

    private int $width = 0;

    private function __construct(
        private string $label,
    ) {
    }

    public static function make(string $label): Button
    {
        return new self($label);
    }

    public function width(float $width): Button
    {
        $clone = clone $this;

        $width = (int)floor($width * 100);

        if ($width > 100) {
            $width = 100;
        }

        $clone->width = $width;

        return $clone;
    }

    public function action(string $name): static
    {
        return $this->param('action', $name);
    }

    public function param(string $key, int|string $value): static
    {
        $clone = clone $this;

        $key = trim($key);
        $value = trim((string) $value);

        $clone->callbackData[] = "$key:$value";

        return $clone;
    }

    public function url(string $url): static
    {
        $clone = clone $this;

        $clone->url = $url;

        return $clone;
    }

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        if (count($this->callbackData) > 0) {
            return [
                'text' => $this->label,
                'callback_data' => implode(';', $this->callbackData),
            ];
        }

        if (isset($this->url)) {
            return [
                'text' => $this->label,
                'url' => $this->url,
            ];
        }

        return [
            'text' => $this->label,
        ];
    }

    public function label(): string
    {
        return $this->label;
    }

    public function get_width(): float
    {
        if ($this->width === 0) {
            return 1;
        }

        return round($this->width / 100, 1);
    }

    public function has_width(): bool
    {
        return $this->width > 0;
    }
}

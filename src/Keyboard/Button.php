<?php

namespace DefStudio\Telegraph\Keyboard;

use DefStudio\Telegraph\Parsers\CallbackQueryDataParserInterface;

class Button
{
    private string $url;
    private string $webAppUrl;

    /** @var string[] */
    private array $callbackData = [];

    private int $width = 0;

    final public function __construct(
        private string $label,
    ) {
    }

    public static function make(string $label): static
    {
        return new static($label);
    }

    public function width(float $percentage): static
    {
        $width = (int)($percentage * 100);

        if ($width > 100) {
            $width = 100;
        }

        $this->width = $width;

        return $this;
    }

    public function action(string $name): static
    {
        return $this->param('action', $name);
    }

    public function param(string $key, int|string $value): static
    {
        $key = trim($key);
        $value = trim((string) $value);

        $this->callbackData[$key] = $value;

        return $this;
    }

    public function url(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function webApp(string $url): static
    {
        $this->webAppUrl = $url;

        return $this;
    }

    /**
     * @return array<string, string|string[]>
     */
    public function toArray(): array
    {
        if (count($this->callbackData) > 0) {
            /** @var CallbackQueryDataParserInterface $parser */
            $parser = app(CallbackQueryDataParserInterface::class);

            return [
                'text' => $this->label,
                'callback_data' => $parser->encode($this->callbackData),
            ];
        }

        if (isset($this->url)) {
            return [
                'text' => $this->label,
                'url' => $this->url,
            ];
        }

        if (isset($this->webAppUrl)) {
            return [
                'text' => $this->label,
                'web_app' => [
                    'url' => $this->webAppUrl,
                ],
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

        return $this->width / 100;
    }

    public function has_width(): bool
    {
        return $this->width > 0;
    }
}

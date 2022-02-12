<?php

namespace DefStudio\Telegraph\Keyboard;

class Button
{
    private string $url;

    /** @var string[] */
    private array $callbackData = [];

    private function __construct(
        private string $label,
    ) {
    }

    public static function make(string $label): Button
    {
        return new self($label);
    }

    public function action(string $name): static
    {
        return $this->param('action', $name);
    }

    public function param(string $key, string $value): static
    {
        $this->callbackData[] = "$key:$value";

        return $this;
    }

    public function url(string $url): static
    {
        $this->url = $url;

        return $this;
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
}

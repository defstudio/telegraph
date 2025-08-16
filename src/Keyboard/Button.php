<?php

namespace DefStudio\Telegraph\Keyboard;

class Button
{
    private string $url;
    private string $webAppUrl;

    private string $loginUrl;

    private string $switchInlineQuery;
    private string $switchInlineQueryCurrentChat;

    private string $copyText;

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

    public function width(float $percentage): Button
    {
        $width = (int) ($percentage * 100);

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

        $this->callbackData[] = "$key:$value";

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

    public function loginUrl(string $url): static
    {
        $this->loginUrl = $url;

        return $this;
    }

    public function switchInlineQuery(string $switchInlineQuery = ''): static
    {
        $this->switchInlineQuery = $switchInlineQuery;

        return $this;
    }

    public function currentChat(): static
    {
        $this->switchInlineQueryCurrentChat = $this->switchInlineQuery;
        unset($this->switchInlineQuery);

        return $this;
    }

    public function copyText(string $text): static
    {
        $this->copyText = $text;

        return $this;
    }

    /**
     * @return array<string, string|string[]>
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

        if (isset($this->webAppUrl)) {
            return [
                'text' => $this->label,
                'web_app' => [
                    'url' => $this->webAppUrl,
                ],
            ];
        }

        if (isset($this->loginUrl)) {
            return [
                'text' => $this->label,
                'login_url' => [
                    'url' => $this->loginUrl,
                ],
            ];
        }

        if (isset($this->switchInlineQuery)) {
            return [
                'text' => $this->label,
                'switch_inline_query' => $this->switchInlineQuery,
            ];
        }

        if (isset($this->switchInlineQueryCurrentChat)) {
            return [
                'text' => $this->label,
                'switch_inline_query_current_chat' => $this->switchInlineQueryCurrentChat,
            ];
        }

        if (isset($this->copyText)) {
            return [
                'text' => $this->label,
                'copy_text' => $this->copyText,
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

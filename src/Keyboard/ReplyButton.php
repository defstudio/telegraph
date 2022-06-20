<?php

/** @noinspection PhpUnused */

namespace DefStudio\Telegraph\Keyboard;

use DefStudio\Telegraph\Enums\ReplyButtonType;

class ReplyButton
{
    private string $type = ReplyButtonType::TEXT;

    private string $webAppUrl;

    /**
     * @var array<string, string>
     */
    private array $pollType;

    private int $width = 0;

    private function __construct(
        private string $label,
    ) {
    }

    public static function make(string $label): ReplyButton
    {
        return new self($label);
    }

    public function width(float $percentage): ReplyButton
    {
        $width = (int)($percentage * 100);

        if ($width > 100) {
            $width = 100;
        }

        $this->width = $width;

        return $this;
    }

    public function webApp(string $url): static
    {
        $this->type = ReplyButtonType::WEB_APP;
        $this->webAppUrl = $url;

        return $this;
    }

    public function requestContact(): static
    {
        $this->type = ReplyButtonType::REQUEST_CONTACT;

        return $this;
    }

    public function requestLocation(): static
    {
        $this->type = ReplyButtonType::REQUEST_LOCATION;

        return $this;
    }

    public function requestPoll(): static
    {
        $this->type = ReplyButtonType::REQUEST_POLL;
        $this->pollType = ['type' => 'regular'];

        return $this;
    }

    public function requestQuiz(): static
    {
        $this->type = ReplyButtonType::REQUEST_POLL;
        $this->pollType = ['type' => 'quiz'];

        return $this;
    }

    /**
     * @return array<string, string|string[]|true>
     */
    public function toArray(): array
    {
        $data = ['text' => $this->label];

        if ($this->type === ReplyButtonType::WEB_APP) {
            $data['web_app'] = [
                'url' => $this->webAppUrl,
            ];
        }

        if ($this->type === ReplyButtonType::REQUEST_CONTACT) {
            $data['request_contact'] = true;
        }

        if ($this->type === ReplyButtonType::REQUEST_LOCATION) {
            $data['request_location'] = true;
        }

        if ($this->type === ReplyButtonType::REQUEST_POLL) {
            $data['request_poll'] = $this->pollType;
        }

        return $data;
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

        return round($this->width / 100, 2);
    }

    public function has_width(): bool
    {
        return $this->width > 0;
    }
}

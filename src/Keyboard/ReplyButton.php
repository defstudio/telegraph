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

    /**
     * @var array<string, string>
     */
    private array $usersType;

    /**
     * @var array<string, mixed>
     */
    private array $chatType;

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

    public function requestUsers(
        int $request_id,
        bool $user_is_bot,
        bool $user_is_premium,
        int $max_quantity,
        bool $request_name,
        bool $request_username,
        bool $request_photo
    ): static
    {
        $this->type = ReplyButtonType::REQUEST_USERS;
        $this->usersType = [
            'request_id' => $request_id,
            'user_is_bot' => $user_is_bot,
            'user_is_premium' => $user_is_premium,
            'max_quantity' => $max_quantity,
            'request_name' => $request_name,
            'request_username' => $request_username,
            'request_photo' => $request_photo,
        ];

        return $this;
    }

    public function requestChat(
        int $request_id,
        bool $chat_is_channel,
        ?bool $chat_is_forum,
        ?bool $chat_has_username,
        ?bool $chat_is_created,
        ?array $user_administrator_rights,
        ?array $bot_administrator_rights,
        ?bool $bot_is_member,
        ?bool $request_title,
        ?bool $request_username,
        ?bool $request_photo
    ): static
    {
        $this->type = ReplyButtonType::REQUEST_CHAT;
        $this->chatType = [
            'request_id' => $request_id,
            'chat_is_channel' => $chat_is_channel,
            'chat_is_forum' => $chat_is_forum,
            'chat_has_username' => $chat_has_username,
            'chat_is_created' => $chat_is_created,
            'user_administrator_rights' => $user_administrator_rights,
            'bot_administrator_rights' => $bot_administrator_rights,
            'bot_is_member' => $bot_is_member,
            'request_title' => $request_title,
            'request_username' => $request_username,
            'request_photo' => $request_photo,
        ];

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

        if ($this->type === ReplyButtonType::REQUEST_USERS) {
            $data['request_users'] = $this->usersType;
        }

        if ($this->type === ReplyButtonType::REQUEST_CHAT) {
            $data['request_chat'] = $this->chatType;
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

        return $this->width / 100;
    }

    public function has_width(): bool
    {
        return $this->width > 0;
    }
}

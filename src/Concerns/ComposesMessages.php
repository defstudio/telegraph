<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\Telegraph;

trait ComposesMessages
{
    public function message(string $message): Telegraph
    {
        return match (config('telegraph.default_parse_mode')) {
            self::PARSE_MARKDOWN => $this->markdown($message),
            default => $this->html($message)
        };
    }

    private function setMessageText(string $message): void
    {
        $this->endpoint ??= self::ENDPOINT_MESSAGE;

        $this->data['text'] = $message;
        $this->data['chat_id'] = $this->getChat()->chat_id;
    }

    public function html(string $message = null): Telegraph
    {
        if ($message !== null) {
            $this->setMessageText($message);
        }

        $this->data['parse_mode'] = 'html';

        return $this;
    }

    public function markdown(string $message = null): Telegraph
    {
        if ($message !== null) {
            $this->setMessageText($message);
        }

        $this->data['parse_mode'] = 'markdown';

        return $this;
    }

    public function reply(int $messageId): Telegraph
    {
        $this->data['reply_to_message_id'] = $messageId;

        return $this;
    }

    public function protected(): Telegraph
    {
        $this->data['protect_content'] = true;

        return $this;
    }

    public function silent(): Telegraph
    {
        $this->data['disable_notification'] = true;

        return $this;
    }

    public function withoutPreview(): Telegraph
    {
        $this->data['disable_web_page_preview'] = true;

        return $this;
    }

    public function deleteMessage(int $messageId): Telegraph
    {
        $this->endpoint = self::ENDPOINT_DELETE_MESSAGE;
        $this->data = [
            'chat_id' => $this->getChat()->chat_id,
            'message_id' => $messageId,
        ];

        return $this;
    }

    public function edit(int $messageId): Telegraph
    {
        $this->endpoint = self::ENDPOINT_EDIT_MESSAGE;
        $this->data['message_id'] = $messageId;

        return $this;
    }

    public function location(float $latitude, float $longitude): Telegraph
    {
        $this->endpoint = self::ENDPOINT_SEND_LOCATION;
        $this->data['latitude'] = $latitude;
        $this->data['longitude'] = $longitude;
        $this->data['chat_id'] = $this->getChat()->chat_id;

        return $this;
    }
}

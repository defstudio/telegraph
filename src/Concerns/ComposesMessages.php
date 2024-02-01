<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\Models\TelegraphChat;
use DefStudio\Telegraph\Telegraph;

trait ComposesMessages
{
    public function message(string $message): Telegraph
    {
        $telegraph = clone $this;

        return match (config('telegraph.default_parse_mode')) {
            self::PARSE_MARKDOWN => $telegraph->markdown($message),
            self::PARSE_MARKDOWNV2 => $telegraph->markdownV2($message),
            default => $telegraph->html($message)
        };
    }

    private function setMessageText(string $message): void
    {
        $this->endpoint ??= self::ENDPOINT_MESSAGE;

        $this->data['text'] = $message;
        $this->data['chat_id'] = $this->getChatId();
    }

    public function html(string $message = null): Telegraph
    {
        $telegraph = clone $this;

        if ($message !== null) {
            $telegraph->setMessageText($message);
        }

        $telegraph->data['parse_mode'] = Telegraph::PARSE_HTML;

        return $telegraph;
    }

    public function markdown(string $message = null): Telegraph
    {
        $telegraph = clone $this;

        if ($message !== null) {
            $telegraph->setMessageText($message);
        }

        $telegraph->data['parse_mode'] = Telegraph::PARSE_MARKDOWN;

        return $telegraph;
    }

    public function markdownV2(string $message = null): Telegraph
    {
        $telegraph = clone $this;

        if ($message !== null) {
            $telegraph->setMessageText($message);
        }

        $telegraph->data['parse_mode'] = Telegraph::PARSE_MARKDOWNV2;

        return $telegraph;
    }

    public function reply(int $messageId): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->data['reply_to_message_id'] = $messageId;

        return $telegraph;
    }

    public function protected(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->data['protect_content'] = true;

        return $telegraph;
    }

    public function silent(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->data['disable_notification'] = true;

        return $telegraph;
    }

    public function withoutPreview(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->data['disable_web_page_preview'] = true;

        return $telegraph;
    }

    public function edit(int $messageId): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_EDIT_MESSAGE;
        $telegraph->data['message_id'] = $messageId;

        return $telegraph;
    }

    public function deleteMessage(int $messageId): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_DELETE_MESSAGE;
        $telegraph->data = [
            'chat_id' => $telegraph->getChatId(),
            'message_id' => $messageId,
        ];

        return $telegraph;
    }

    public function pinMessage(int $messageId): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_PIN_MESSAGE;
        $telegraph->data = [
            'chat_id' => $telegraph->getChatId(),
            'message_id' => $messageId,
        ];

        return $telegraph;
    }

    public function unpinMessage(int $messageId): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_UNPIN_MESSAGE;
        $telegraph->data = [
            'chat_id' => $telegraph->getChatId(),
            'message_id' => $messageId,
        ];

        return $telegraph;
    }

    public function unpinAllMessages(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_UNPIN_ALL_MESSAGES;
        $telegraph->data = [
            'chat_id' => $telegraph->getChatId(),
        ];

        return $telegraph;
    }

    public function forwardMessage(TelegraphChat|int $fromChat, int $messageId): Telegraph
    {
        $fromChatId = is_int($fromChat) ? $fromChat : $fromChat->chat_id;

        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_FORWARD_MESSAGE;
        $telegraph->data = [
            'chat_id' => $telegraph->getChatId(),
            'message_id' => $messageId,
            'from_chat_id' => $fromChatId,
        ];

        return $telegraph;
    }

    public function copyMessage(TelegraphChat|int $fromChat, int $messageId): Telegraph
    {
        $fromChatId = is_int($fromChat) ? $fromChat : $fromChat->chat_id;

        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_COPY_MESSAGE;
        $telegraph->data = [
            'chat_id' => $telegraph->getChatId(),
            'message_id' => $messageId,
            'from_chat_id' => $fromChatId,
        ];

        return $telegraph;
    }
}

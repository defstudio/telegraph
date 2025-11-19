<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Games;

use DefStudio\Telegraph\Concerns\BuildsFromTelegraphClass;
use DefStudio\Telegraph\Exceptions\InvoiceException;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Support\Facades\Validator;

class TelegraphGamePayload extends Telegraph
{
    use BuildsFromTelegraphClass;

    public function game(string $shortName): static
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_SEND_GAME;

        $telegraph->data['game_short_name'] = $shortName;

        return $telegraph;
    }

    public function businessConnectionId(string $id): static
    {
        $telegraph = clone $this;

        $telegraph->data['business_connection_id'] = $id;

        return $telegraph;
    }

    public function messageThreadId(string $id): static
    {
        $telegraph = clone $this;

        $telegraph->data['message_thread_id'] = $id;

        return $telegraph;
    }

    public function messageEffectId(string $id): static
    {
        $telegraph = clone $this;

        $telegraph->data['message_effect_id'] = $id;

        return $telegraph;
    }

    public function disableNotification(bool $disable = true): static
    {
        $telegraph = clone $this;

        $telegraph->data['disable_notification'] = $disable;

        return $telegraph;
    }

    public function protectContent(bool $protect = true): static
    {
        $telegraph = clone $this;

        $telegraph->data['protect_content'] = $protect;

        return $telegraph;
    }

    public function allowPaidBroadcast(bool $allow = true): static
    {
        $telegraph = clone $this;

        $telegraph->data['allow_paid_broadcast'] = $allow;

        return $telegraph;
    }

    protected function prepareData(): array
    {
        $data = parent::prepareData();

        if (empty($data['chat_id']) && $this->endpoint === self::ENDPOINT_SEND_GAME) {
            $data['chat_id'] = $this->getChatId();
        }

        $validator = Validator::make($data, [
            'business_connection_id' => ['nullable', 'string'],
            'message_thread_id' => ['nullable', 'string'],
            'game_short_name' => ['required', 'string'],
            'disable_notification' => ['nullable', 'boolean'],
            'protect_content' => ['nullable', 'boolean'],
            'allow_paid_broadcast' => ['nullable', 'boolean'],
            'message_effect_id' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            throw InvoiceException::validationError($validator->messages());
        }

        return $data;
    }
}

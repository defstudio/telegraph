<?php

/** @noinspection PhpDocMissingThrowsInspection */

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Telegraph;

/**
 * @mixin Telegraph
 */
trait ManagesKeyboards
{
    protected Keyboard|null $keyboard = null;

    /**
     * @param array<array<array<string, string>>>|Keyboard|callable(Keyboard):Keyboard $keyboard
     */
    public function keyboard(callable|array|Keyboard $keyboard): Telegraph
    {
        if (is_callable($keyboard)) {
            $keyboard = $keyboard(Keyboard::make());
        }

        if (is_array($keyboard)) {
            $keyboard = Keyboard::fromArray($keyboard);
        }

        $this->data['reply_markup'] = [
            'inline_keyboard' => $keyboard->toArray(),
        ];

        return $this;
    }

    /**
     * @param Keyboard|callable(Keyboard):Keyboard $newKeyboard
     */
    public function replaceKeyboard(int $messageId, Keyboard|callable $newKeyboard): Telegraph
    {
        if (is_callable($newKeyboard)) {
            $newKeyboard = $newKeyboard(Keyboard::make());
        }

        if ($newKeyboard->isEmpty()) {
            $replyMarkup = '';
        } else {
            $replyMarkup = ['inline_keyboard' => $newKeyboard->toArray()];
        }

        $this->endpoint = self::ENDPOINT_REPLACE_KEYBOARD;
        $this->data = [
            'chat_id' => $this->getChat()->chat_id,
            'message_id' => $messageId,
            'reply_markup' => $replyMarkup,
        ];

        return $this;
    }

    public function deleteKeyboard(int $messageId): Telegraph
    {
        return $this->replaceKeyboard($messageId, Keyboard::make());
    }
}

<?php

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
     * @param array<array<array<string, string>>>|Keyboard $keyboard
     */
    public function keyboard(array|Keyboard $keyboard): Telegraph
    {
        if (is_array($keyboard)) {
            $keyboard = Keyboard::fromArray($keyboard);
        }

        $this->keyboard = $keyboard;

        return $this;
    }

    public function replaceKeyboard(string $messageId, Keyboard $newKeyboard): Telegraph
    {
        if ($newKeyboard->isEmpty()) {
            $replyMarkup = null;
        } else {
            $replyMarkup = json_encode(['inline_keyboard' => $newKeyboard->toArray()]);
        }

        $this->endpoint = self::ENDPOINT_REPLACE_KEYBOARD;
        $this->data = [
            'chat_id' => $this->getChat()->chat_id,
            'message_id' => $messageId,
            'reply_markup' => $replyMarkup,
        ];

        return $this;
    }

    public function deleteKeyboard(string $messageId): Telegraph
    {
        return $this->replaceKeyboard($messageId, Keyboard::make());
    }
}

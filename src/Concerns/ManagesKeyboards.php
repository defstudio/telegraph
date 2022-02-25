<?php

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\Exceptions\TelegraphException;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Telegraph;

/**
 * @mixin Telegraph
 */
trait ManagesKeyboards
{
    /** @var array<array<array<string, string>>> */
    protected array $keyboard;

    /**
     * @param array<array<array<string, string>>>|Keyboard $keyboard
     */
    public function keyboard(array|Keyboard $keyboard): Telegraph
    {
        $this->keyboard = is_array($keyboard) ? $keyboard : $keyboard->toArray();

        return $this;
    }

    /**
     * @throws TelegraphException
     */
    public function replaceKeyboard(string $messageId, Keyboard $newKeyboard): Telegraph
    {
        if (empty($this->chat)) {
            throw TelegraphException::missingChat();
        }

        if ($newKeyboard->isEmpty()) {
            $replyMarkup = null;
        } else {
            $replyMarkup = json_encode(['inline_keyboard' => $newKeyboard->toArray()]);
        }

        $this->endpoint = self::ENDPOINT_REPLACE_KEYBOARD;
        $this->data = [
            'chat_id' => $this->chat->chat_id,
            'message_id' => $messageId,
            'reply_markup' => $replyMarkup,
        ];

        return $this;
    }

    /**
     * @throws TelegraphException
     */
    public function deleteKeyboard(string $messageId): Telegraph
    {
        return $this->replaceKeyboard($messageId, Keyboard::make());
    }
}

<?php

/** @noinspection PhpUnused */

/** @noinspection PhpDocMissingThrowsInspection */

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Proxies;

use DefStudio\Telegraph\Exceptions\KeyboardException;
use DefStudio\Telegraph\Keyboard\ReplyButton;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;

/**
 * @internal
 *
 * @mixin ReplyButton
 */
class ReplyKeyboardButtonProxy extends ReplyKeyboard
{
    private ReplyButton $button;

    public function __construct(ReplyKeyboard $proxyed, ReplyButton $button)
    {
        parent::__construct();
        $this->button = $button;
        $this->buttons = $proxyed->buttons;
    }

    /**
     * @param array<array-key, mixed> $arguments
     */
    public function __call(string $name, array $arguments): ReplyKeyboardButtonProxy
    {
        if (!method_exists($this->button, $name)) {
            throw KeyboardException::undefinedMethod($name);
        }

        $clone = $this->clone();

        $clone->button->$name(...$arguments);

        return $clone;
    }

    protected function clone(): ReplyKeyboardButtonProxy
    {
        return new self(parent::clone(), $this->button);
    }
}

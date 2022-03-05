<?php

/** @noinspection PhpDocMissingThrowsInspection */

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Proxies;

use DefStudio\Telegraph\Exceptions\KeyboardException;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;

/**
 * @internal
 *
 * @mixin Button
 */
class KeyboardButtonProxy extends Keyboard
{
    private Button $button;

    public function __construct(Keyboard $proxyed, Button $button)
    {
        parent::__construct();
        $this->button = $button;
        $this->buttons = $proxyed->buttons;
    }

    /**
     * @param array<array-key, mixed> $arguments
     */
    public function __call(string $name, array $arguments): KeyboardButtonProxy
    {
        if (!method_exists($this->button, $name)) {
            throw KeyboardException::undefinedMethod($name);
        }

        $clone = $this->clone();

        $clone->button->$name(...$arguments);

        return $clone;
    }

    protected function clone(): KeyboardButtonProxy
    {
        return new self(parent::clone(), $this->button);
    }
}

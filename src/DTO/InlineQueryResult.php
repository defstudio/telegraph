<?php

namespace DefStudio\Telegraph\DTO;

use DefStudio\Telegraph\Keyboard\Keyboard;

abstract class InlineQueryResult
{
    protected string $type;
    protected string $id;


    protected Keyboard|null $keyboard = null;

    protected function __construct()
    {
    }

    abstract protected function data(): array;

    public function keyboard(Keyboard $keyboard): static
    {
        $this->keyboard = $keyboard;

        return $this;
    }

    public function toArray(): array
    {
        $data = array_filter($this->data());

        if ($this->keyboard !== null && $this->keyboard->isFilled()) {
            $data['reply_markup'] = [
                'inline_keyboard' => $this->keyboard->toArray(),
            ];
        }

        return $data;
    }
}

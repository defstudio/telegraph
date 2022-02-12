<?php

namespace DefStudio\Telegraph\Keyboard;

use Illuminate\Support\Collection;

class Keyboard
{
    private int $chunk = 1;

    /** @var Button[][] */
    private array $rows = [];

    /** @var Button[] */
    private array $buttons = [];

    private function __construct()
    {
    }

    public static function make(): Keyboard
    {
        return new self();
    }

    /**
     * @param array<Button> $buttons
     *
     * @return Keyboard
     */
    public function row(array $buttons): Keyboard
    {
        $this->rows[] = $buttons;

        return $this;
    }

    public function chunk(int $chunk): Keyboard
    {
        $this->chunk = $chunk;

        return $this;
    }

    /**
     * @param Button[] $buttons
     *
     * @return Keyboard
     */
    public function buttons(array $buttons): Keyboard
    {
        $this->buttons = $buttons;

        return $this;
    }

    /**
     * @return string[][][]
     */
    public function toArray(): array
    {
        collect($this->buttons)
            ->chunk($this->chunk)
            ->each(function (Collection $buttons) {
                $buttons = $buttons->toArray();

                /** @var Button[] $buttons */

                $this->rows[] = $buttons;
            });

        $keyboard = [];

        foreach ($this->rows as $buttons) {
            $row = [];

            foreach ($buttons as $button) {
                $row[] = $button->toArray();
            }

            $keyboard[] = $row;
        }

        return $keyboard;
    }
}

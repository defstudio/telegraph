<?php

namespace DefStudio\Telegraph\Keyboard;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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
     * @param string[][][] $arrayKeyboard
     *
     * @return Keyboard
     */
    public static function fromArray(array $arrayKeyboard): Keyboard
    {
        $keyboard = self::make();

        foreach ($arrayKeyboard as $buttons) {
            $rowButtons = [];

            foreach ($buttons as $button) {
                $rowButton = Button::make($button['text']);

                if (array_key_exists("callback_data", $button)) {
                    $params = explode(";", $button['callback_data']);

                    foreach ($params as $param) {
                        $key = Str::of($param)->before(':');
                        $value = Str::of($param)->after(':');

                        $rowButton->param($key, $value);
                    }
                }

                if (array_key_exists("url", $button)) {
                    $rowButton = $rowButton->url($button['url']);
                }

                $rowButtons[] = $rowButton;
            }

            $keyboard = $keyboard->row($rowButtons);
        }

        return $keyboard;
    }

    /**
     * @param array<Button> $buttons
     *
     * @return Keyboard
     */
    public function row(array $buttons): Keyboard
    {
        $clone = clone $this;
        $clone->rows[] = $buttons;

        return $clone;
    }

    public function chunk(int $chunk): Keyboard
    {
        $clone = clone $this;
        $clone->chunk = $chunk;

        return $clone;
    }

    /**
     * @param Button[] $buttons
     *
     * @return Keyboard
     */
    public function buttons(array $buttons): Keyboard
    {
        $clone = clone $this;
        $clone->buttons = $buttons;

        return $clone;
    }

    public function replaceButton(string $label, Button $newButton): Keyboard
    {
        $clone = clone $this;

        foreach ($clone->buttons as $index => $button) {
            if ($button->label() == $label) {
                $clone->buttons[$index] = $newButton;
            }
        }

        foreach ($clone->rows as $rowIndex => $buttons) {
            foreach ($buttons as $buttonIndex => $button) {
                if ($button->label() == $label) {
                    $clone->rows[$rowIndex][$buttonIndex] = $newButton;
                }
            }
        }

        return $clone;
    }

    public function deleteButton(string $label): Keyboard
    {
        $clone = clone $this;

        $toDelete = [];
        foreach ($clone->buttons as $index => $button) {
            if ($button->label() == $label) {
                $toDelete[] = $index;
            }
        }

        foreach ($toDelete as $indexToDelete) {
            unset($clone->buttons[$indexToDelete]);
        }

        $clone->buttons = array_values($clone->buttons);


        foreach ($clone->rows as $rowIndex => $buttons) {
            $toDelete = [];
            foreach ($buttons as $buttonIndex => $button) {
                if ($button->label() == $label) {
                    $toDelete[] = $buttonIndex;
                }
            }

            foreach ($toDelete as $indexToDelete) {
                unset($clone->rows[$rowIndex][$indexToDelete]);
            }

            $clone->rows[$rowIndex] = array_values($clone->rows[$rowIndex]);
        }

        return $clone;
    }

    public function flatten(): Keyboard
    {
        $clone = clone $this;

        $newButtonSet = [];

        foreach ($clone->rows as $buttons) {
            foreach ($buttons as $button) {
                $newButtonSet[] = $button;
            }
        }

        foreach ($clone->buttons as $button) {
            $newButtonSet[] = $button;
        }

        $clone->buttons = $newButtonSet;

        $clone->rows = [];

        return $clone;
    }

    public function isEmpty(): bool
    {
        return count($this->flatten()->buttons) === 0;
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

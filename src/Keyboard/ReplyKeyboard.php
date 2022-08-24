<?php

/** @noinspection PhpUnused */

namespace DefStudio\Telegraph\Keyboard;

use DefStudio\Telegraph\Proxies\ReplyKeyboardButtonProxy;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class ReplyKeyboard implements Arrayable
{
    /** @var Collection<array-key, ReplyButton> */
    protected Collection $buttons;

    protected bool $resize = false;
    protected bool $oneTime = false;
    protected bool $selective = false;
    protected string|null $inputPlaceholder = null;

    public function __construct()
    {
        /* @phpstan-ignore-next-line  */
        $this->buttons = collect();
    }

    public static function make(): self
    {
        return new self();
    }

    /**
     * @param callable(ReplyKeyboard $keyboard): ReplyKeyboard $callback
     */
    public function when(bool $condition, callable $callback): self
    {
        if ($condition) {
            return $callback($this);
        }

        return $this;
    }

    protected function clone(): self
    {
        $clone = self::make();
        $clone->buttons = $this->buttons;

        return $clone;
    }

    /**
     * @param array<array-key, array<array-key, array{text: string, request_contact?: bool, request_location?: bool, request_poll?: string[], web_app?: string[]}>> $arrayKeyboard
     *
     * @return self
     */
    public static function fromArray(array $arrayKeyboard): self
    {
        $keyboard = self::make();

        foreach ($arrayKeyboard as $buttons) {
            $rowButtons = [];

            foreach ($buttons as $button) {
                $rowButton = ReplyButton::make($button['text']);

                if ($button['request_contact'] ?? false) {
                    $rowButton = $rowButton->requestContact();
                }

                if ($button['request_location'] ?? false) {
                    $rowButton = $rowButton->requestLocation();
                }

                if ($button['request_poll'] ?? false) {
                    if (($button['request_poll']['type'] ?? false) == 'quiz') {
                        $rowButton = $rowButton->requestQuiz();
                    } else {
                        $rowButton = $rowButton->requestPoll();
                    }
                }

                if (array_key_exists("web_app", $button)) {
                    $rowButton = $rowButton->webApp($button['web_app']['url']);
                }

                $rowButtons[] = $rowButton;
            }

            $keyboard = $keyboard->row($rowButtons);
        }

        return $keyboard;
    }

    public function resize(bool $resize = true): self
    {
        $clone = $this->clone();

        $clone->resize = $resize;

        return $clone;
    }

    public function selective(bool $selective = true): self
    {
        $clone = $this->clone();

        $clone->selective = $selective;

        return $clone;
    }

    public function inputPlaceholder(string $text): self
    {
        $clone = $this->clone();

        $clone->inputPlaceholder = $text;

        return $clone;
    }

    public function oneTime(bool $oneTime = true): self
    {
        $clone = $this->clone();

        $clone->oneTime = $oneTime;

        return $clone;
    }

    /**
     * @param array<array-key, ReplyButton>|Collection<array-key, ReplyButton> $buttons
     *
     * @return self
     */
    public function row(array|Collection $buttons): self
    {
        $clone = $this->clone();

        if (is_array($buttons)) {
            $buttons = collect($buttons);
        }

        $buttonWidth = 1 / $buttons->count();

        $buttons = $buttons->map(fn (ReplyButton $button) => $button->width($buttonWidth));

        $this->buttons->push(...$buttons);

        return $clone;
    }

    public function chunk(int $chunk): self
    {
        $clone = $this->clone();

        $buttonWidth = 1 / $chunk;

        $clone->buttons = $this->buttons->map(fn (ReplyButton $button) => $button->width($buttonWidth));

        return $clone;
    }

    /**
     * @param array<array-key, ReplyButton>|Collection<array-key, ReplyButton> $buttons $buttons
     *
     * @return self
     */
    public function buttons(array|Collection $buttons): self
    {
        $clone = $this->clone();

        if (is_array($buttons)) {
            $buttons = collect($buttons);
        }

        $clone->buttons->push(...$buttons);

        return $clone;
    }

    public function replaceButton(string $label, ReplyButton $newButton): self
    {
        $clone = $this->clone();

        $clone->buttons = $clone->buttons->map(function (ReplyButton $button) use ($newButton, $label) {
            if ($button->label() == $label) {
                if (!$newButton->has_width()) {
                    $newButton = $newButton->width($button->get_width());
                }

                return $newButton;
            }

            return $button;
        });

        return $clone;
    }

    public function deleteButton(string $label): self
    {
        $clone = $this->clone();

        /* @phpstan-ignore-next-line  */
        $clone->buttons = $clone->buttons->reject(fn (ReplyButton $button) => $button->label() == $label);

        return $clone;
    }

    public function button(string $label): ReplyKeyboardButtonProxy
    {
        $button = ReplyButton::make($label);

        $clone = $this->clone();
        $clone->buttons[] = $button;

        return new ReplyKeyboardButtonProxy($clone, $button);
    }

    public function flatten(): self
    {
        $clone = $this->clone();

        $clone->buttons = $clone->buttons->map(fn (ReplyButton $button) => $button->width(1));

        return $clone;
    }

    public function isEmpty(): bool
    {
        return $this->buttons->isEmpty();
    }

    public function isFilled(): bool
    {
        return !$this->isEmpty();
    }

    /**
     * @return array<array-key, array<array-key, array<string|string[]|bool>>>
     */
    public function toArray(): array
    {
        $keyboard = [];

        $row = [];
        $rowWidth = 0;

        $this->buttons->each(function (ReplyButton $button) use (&$keyboard, &$row, &$rowWidth): void {
            if ($rowWidth + $button->get_width() > 1) {
                $keyboard[] = $row;
                $row = [];
                $rowWidth = 0;
            }

            $row[] = $button->toArray();
            $rowWidth += $button->get_width();
        });

        $keyboard[] = $row;

        return $keyboard;
    }

    /**
     * @return array<string, string|bool>
     */
    public function options(): array
    {
        return array_filter([
             'resize_keyboard' => $this->resize,
             'one_time_keyboard' => $this->oneTime,
             'selective' => $this->selective,
             'input_field_placeholder' => $this->inputPlaceholder,
         ]);
    }
}

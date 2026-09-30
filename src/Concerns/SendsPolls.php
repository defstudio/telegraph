<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

use Carbon\CarbonInterface;
use DefStudio\Telegraph\Exceptions\TelegraphPollException;
use DefStudio\Telegraph\ScopedPayloads\TelegraphPollPayload;
use DefStudio\Telegraph\ScopedPayloads\TelegraphQuizPayload;

/**
 * @mixin TelegraphPollPayload
 * @mixin TelegraphQuizPayload
 */
trait SendsPolls
{
    public function option(string $option): static
    {
        $telegraph = clone $this;

        /** @phpstan-ignore-next-line */
        if (count($telegraph->data['options']) === 12) {
            throw TelegraphPollException::tooManyOptions();
        }

        if (strlen($option) > 100) {
            throw TelegraphPollException::optionMaxLengthExceeded($option);
        }

        /** @phpstan-ignore-next-line */
        $telegraph->data['options'][] = $option;

        return $telegraph;
    }

    /**
     * Summary of options
     *
     * Telegram allows a maximum of 12 options for a poll/quiz,
     * and each option must not exceed 100 characters in length.
     * This method checks these constraints before adding the options
     * to the poll/quiz. If the constraints are violated, it throws a
     * TelegraphPollException with an appropriate message.
     *
     * @param array<string> $options
     * @return static
     */
    public function options(array $options): static
    {
        $telegraph = clone $this;

        /** @phpstan-ignore-next-line */
        if ((count($telegraph->data['options']) + count($options)) > 12) {
            throw TelegraphPollException::tooManyOptions();
        }


        foreach ($options as $option_key => $option_val) {
            if (is_string($option_key)) {
                $option = $option_key;
            } else {
                $option = $option_val;
            }

            /** @phpstan-ignore-next-line */
            if (strlen($option) > 100) {
                throw TelegraphPollException::optionMaxLengthExceeded($option);
            }
        }

        /** @phpstan-ignore-next-line */
        $telegraph->data['options'] = array_merge($telegraph->data['options'], $options);

        return $telegraph;
    }

    public function disableAnonymous(): static
    {
        $telegraph = clone $this;
        $telegraph->data['is_anonymous'] = false;

        return $telegraph;
    }

    public function validUntil(CarbonInterface $endTime): self
    {
        if ($endTime->subSeconds(5)->isPast()) {
            throw TelegraphPollException::durationTooShort($endTime);
        }

        if ($endTime->subSeconds(600)->isFuture()) {
            throw TelegraphPollException::durationTooLong($endTime);
        }

        $telegraph = clone $this;
        $telegraph->data['close_date'] = $endTime->timestamp;

        return $telegraph;
    }
}

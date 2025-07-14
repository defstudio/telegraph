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

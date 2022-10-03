<?php

namespace DefStudio\Telegraph\Exceptions;

use Carbon\CarbonInterface;

class TelegraphPollException extends \Exception
{
    public static function tooManyOptions(): self
    {
        return new self("Maximum options count of 10 exceeded for a Telegram poll");
    }

    public static function optionMaxLengthExceeded(string $option): self
    {
        return new self("Max option length (100 chars) exceeded for option [$option]");
    }

    public static function explanationMaxLengthExceeded(): self
    {
        return new self("Max explanation length (200 chars) exceeded");
    }

    public static function onlyOneCorrectAnswerAllowed(string $chosenOption): self
    {
        return new self("A correct coption has already been chosen for the quiz: [$chosenOption]");
    }

    public static function durationTooShort(CarbonInterface $carbon): self
    {
        return new self("Poll/Quiz end time [$carbon] is too early, min duration is 5 seconds");
    }

    public static function durationTooLong(CarbonInterface $carbon): self
    {
        return new self("Poll/Quiz end time [$carbon] is too late, min duration is 600 seconds");
    }
}

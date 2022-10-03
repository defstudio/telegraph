<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\ScopedPayloads;

use DefStudio\Telegraph\Concerns\BuildsFromTelegraphClass;
use DefStudio\Telegraph\Concerns\SendsPolls;
use DefStudio\Telegraph\Exceptions\TelegraphPollException;
use DefStudio\Telegraph\Telegraph;

class TelegraphQuizPayload extends Telegraph
{
    use BuildsFromTelegraphClass;
    use SendsPolls {
        option as protected _createOption;
    }

    public function quiz(string $question): static
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_SEND_POLL;
        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;
        $telegraph->data['type'] = 'quiz';
        $telegraph->data['options'] = [];
        $telegraph->data['question'] = $question;

        return $telegraph;
    }

    public function option(string $option, bool $correct = false): static
    {
        $telegraph = self::_createOption($option);

        if ($correct) {
            if (isset($telegraph->data['correct_option_id'])) {
                /** @phpstan-ignore-next-line */
                throw TelegraphPollException::onlyOneCorrectAnswerAllowed($telegraph->data['options'][$telegraph->data['correct_option_id']]);
            }

            /** @phpstan-ignore-next-line */
            $telegraph->data['correct_option_id'] = count($telegraph->data['options']) - 1;
        }

        return $telegraph;
    }

    public function explanation(string $text): static
    {
        if (strlen($text) > 200) {
            throw TelegraphPollException::explanationMaxLengthExceeded();
        }

        $telegraph = clone $this;
        $telegraph->data['explanation'] = $text;

        return $telegraph;
    }
}

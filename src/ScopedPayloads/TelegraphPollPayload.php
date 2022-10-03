<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\ScopedPayloads;

use DefStudio\Telegraph\Concerns\BuildsFromTelegraphClass;
use DefStudio\Telegraph\Concerns\SendsPolls;
use DefStudio\Telegraph\Telegraph;

class TelegraphPollPayload extends Telegraph
{
    use BuildsFromTelegraphClass;
    use SendsPolls;

    public function poll(string $question): static
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_SEND_POLL;
        $telegraph->data['chat_id'] = $telegraph->getChat()->chat_id;
        $telegraph->data['options'] = [];
        $telegraph->data['question'] = $question;

        return $telegraph;
    }

    public function allowMultipleAnswers(): static
    {
        $telegraph = clone $this;
        $telegraph->data['allows_multiple_answers'] = true;

        return $telegraph;
    }
}

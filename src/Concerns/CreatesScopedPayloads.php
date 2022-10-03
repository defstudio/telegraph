<?php

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\ScopedPayloads\TelegraphPollPayload;
use DefStudio\Telegraph\ScopedPayloads\TelegraphQuizPayload;

trait CreatesScopedPayloads
{
    public function poll(string $question): TelegraphPollPayload
    {
        $poolPayload = TelegraphPollPayload::makeFrom($this);

        return $poolPayload->poll($question);
    }

    public function quiz(string $question): TelegraphQuizPayload
    {
        $quizPayload = TelegraphQuizPayload::makeFrom($this);

        return $quizPayload->quiz($question);
    }
}

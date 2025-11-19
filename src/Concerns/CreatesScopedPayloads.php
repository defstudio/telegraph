<?php

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\Games\TelegraphGamePayload;
use DefStudio\Telegraph\Payments\TelegraphInvoicePayload;
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

    public function invoice(string $title): TelegraphInvoicePayload
    {
        $invoicePayload = TelegraphInvoicePayload::makeFrom($this);

        return $invoicePayload->invoice($title);
    }

    public function game(string $shortName): TelegraphGamePayload
    {
        $gamePayload = TelegraphGamePayload::makeFrom($this);

        return $gamePayload->game($shortName);
    }
}

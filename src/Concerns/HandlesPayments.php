<?php

/** @noinspection PhpUnnecessaryLocalVariableInspection */

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\DTO\AnswerPreCheckoutQuery;
use DefStudio\Telegraph\DTO\Invoice;
use DefStudio\Telegraph\Telegraph;

/**
 * @mixin Telegraph
 */
trait HandlesPayments
{
    public function invoice(Invoice $invoice): self
    {
        $telegraph = clone $this;

        $invoice->chatId = $telegraph->getChatId();

        $telegraph->endpoint = self::ENDPOINT_INVOICE;
        $telegraph->data = $invoice->toArray();

        return $telegraph;
    }

    public function successPreCheckoutQuery(AnswerPreCheckoutQuery $query): self
    {
        $query->ok = true;

        $telegraph = clone $this;
        $telegraph->endpoint = self::ENDPOINT_ANSWER_PRE_CHECKOUT_QUERY;
        $telegraph->data = $query->toArray();

        return $telegraph;
    }

    public function errorPreCheckoutQuery(AnswerPreCheckoutQuery $query, string $message = 'Pre checkout error.'): self
    {
        $query->ok = false;
        $query->errorMessage = $message;

        $telegraph = clone $this;
        $telegraph->endpoint = self::ENDPOINT_ANSWER_PRE_CHECKOUT_QUERY;
        $telegraph->data = $query->toArray();

        return $telegraph;
    }
}

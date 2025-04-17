<?php


/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\Telegraph;

/**
 * @mixin Telegraph
 */
trait InteractsWithPayments
{
    public function refundStarPayment(string $userId, string $telegramPaymentChargeId): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_REFUND_STAR_PAYMENT;
        $telegraph->data['user_id'] = $userId;
        $telegraph->data['telegram_payment_charge_id'] = $telegramPaymentChargeId;

        return $telegraph;
    }
}

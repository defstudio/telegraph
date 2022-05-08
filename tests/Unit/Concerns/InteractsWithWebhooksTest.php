<?php

use DefStudio\Telegraph\Telegraph;

it('can register a webhook', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->bot(make_bot())->registerWebhook())
        ->toMatchTelegramSnapshot();
});

it('can get webhook debug info', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->bot(make_bot())->getWebhookDebugInfo())
        ->toMatchTelegramSnapshot();
});

it('can reply to a webhook call', function () {
    expect(fn (Telegraph $telegraph) => $telegraph->bot(make_bot())->replyWebhook(2123456, 'foo'))
        ->toMatchTelegramSnapshot();
});

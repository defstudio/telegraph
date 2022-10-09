<?php

namespace DefStudio\Telegraph\Handlers;

class EmptyWebhookHandler extends WebhookHandler
{

    protected function systemLanguage(): void
    {
        \App::setLocale('nl');
    }
}

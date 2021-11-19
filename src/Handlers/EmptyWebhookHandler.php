<?php

namespace DefStudio\LaravelTelegraph\Handlers;

class EmptyWebhookHandler extends WebhookHandler
{
    protected function actionName()
    {
        //This method will process a webhook action called "actionName"

        $this->reply("response message to be displayed");
    }
}

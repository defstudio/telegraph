<?php

namespace DefStudio\Telegraph\Handlers;

class EmptyWebhookHandler extends WebhookHandler
{
    protected function actionName(): void
    {
        //This method will process a webhook action called "actionName"

        $this->reply("response message to be displayed");
    }
}

<?php

declare(strict_types=1);

namespace DefStudio\Telegraph\Tests\Support;

use DefStudio\Telegraph\Handlers\WebhookHandler;
use Illuminate\Support\Stringable;

class TestEntitiesWebhookHandler extends WebhookHandler
{
    protected function handleChatMessage(Stringable $text): void
    {
        /** @var \DefStudio\Telegraph\DTO\Entity $entity */
        $entity = $this->message->entities()->first();

        $fromText = $text->substr($entity->offset(), $entity->length());
        $fromEntity = $entity->url();

        $this->chat->html(implode('. ', [
            'URL from text: ' . $fromText,
            'URL from entity: ' . $fromEntity,
        ]))->send();
    }
}

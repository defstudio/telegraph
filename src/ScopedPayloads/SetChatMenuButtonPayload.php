<?php

namespace DefStudio\Telegraph\ScopedPayloads;

use DefStudio\Telegraph\Concerns\BuildsFromTelegraphClass;
use DefStudio\Telegraph\Telegraph;

class SetChatMenuButtonPayload extends Telegraph
{
    use BuildsFromTelegraphClass;

    public function default(): self
    {
        $telegraph = clone $this;

        $telegraph->data['menu_button'] = [
            'type' => 'default',
        ];

        return $telegraph;
    }

    public function commands(): self
    {
        $telegraph = clone $this;

        $telegraph->data['menu_button'] = [
            'type' => 'commands',
        ];

        return $telegraph;
    }

    public function webApp(string $text, string $url): self
    {
        $telegraph = clone $this;

        $telegraph->data['menu_button'] = [
            'type' => 'web_app',
            'text' => $text,
            'web_app' => [
                'url' => $url,
            ],
        ];

        return $telegraph;
    }
}

<?php

namespace DefStudio\Telegraph\ScopedPayloads;

use DefStudio\Telegraph\Concerns\BuildsFromTelegraphClass;
use DefStudio\Telegraph\Telegraph;

class DicePayload extends Telegraph
{
    use BuildsFromTelegraphClass;

    public function emoji(string $emoji): static
    {
        $telegraph = clone $this;

        $telegraph->data['emoji'] = $emoji;

        return $telegraph;
    }
}

<?php

namespace DefStudio\Telegraph\ScopedPayloads;

use DefStudio\Telegraph\Concerns\BuildsFromTelegraphClass;
use DefStudio\Telegraph\Telegraph;

class PhotoPayload extends Telegraph
{
    use BuildsFromTelegraphClass;

    public function caption(string $caption): static
    {
        $telegraph = clone $this;

        $telegraph->data['caption'] = $caption;

        return $telegraph;
    }

    public function spoiler(): static
    {
        $telegraph = clone $this;

        $telegraph->data['has_spoiler'] = true;

        return $telegraph;
    }
}

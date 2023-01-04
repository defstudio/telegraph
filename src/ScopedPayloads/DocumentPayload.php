<?php

namespace DefStudio\Telegraph\ScopedPayloads;

use DefStudio\Telegraph\Concerns\BuildsFromTelegraphClass;
use DefStudio\Telegraph\Telegraph;

class DocumentPayload extends Telegraph
{
    use BuildsFromTelegraphClass;

    public function caption(string $caption): static
    {
        $telegraph = clone $this;

        $telegraph->data['caption'] = $caption;

        return $telegraph;
    }

    public function withoutContentTypeDetection(): self
    {
        $telegraph = clone $this;

        $telegraph->data['disable_content_type_detection'] = 1;

        return $telegraph;
    }
}

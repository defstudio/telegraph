<?php

namespace DefStudio\Telegraph\Support\Testing\Fakes;

use DefStudio\Telegraph\Concerns\FakesRequests;
use DefStudio\Telegraph\ScopedPayloads\AudioPayload;

class AudioPayloadFake extends AudioPayload
{
    use FakesRequests;

    public function caption(string $caption): static
    {
        $telegraph = clone $this;

        $telegraph->data['caption'] = $caption;

        return $telegraph;
    }

    public function duration(int $duration): static
    {
        $telegraph = clone $this;

        $telegraph->data['duration'] = $duration;

        return $telegraph;
    }

    public function performer(string $performer): static
    {
        $telegraph = clone $this;

        $telegraph->data['performer'] = $performer;

        return $telegraph;
    }

    public function title(string $title): static
    {
        $telegraph = clone $this;

        $telegraph->data['title'] = $title;

        return $telegraph;
    }
}

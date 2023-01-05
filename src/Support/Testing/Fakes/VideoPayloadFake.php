<?php

namespace DefStudio\Telegraph\Support\Testing\Fakes;

use DefStudio\Telegraph\Concerns\FakesRequests;
use DefStudio\Telegraph\ScopedPayloads\VideoPayload;

class VideoPayloadFake extends VideoPayload
{
    use FakesRequests;

    public function duration(int $duration): static
    {
        $telegraph = clone $this;

        $telegraph->data['duration'] = $duration;

        return $telegraph;
    }

    public function width(int $width): static
    {
        $telegraph = clone $this;

        $telegraph->data['width'] = $width;

        return $telegraph;
    }

    public function height(int $height): static
    {
        $telegraph = clone $this;

        $telegraph->data['height'] = $height;

        return $telegraph;
    }

    public function caption(string $caption): static
    {
        $telegraph = clone $this;

        $telegraph->data['caption'] = $caption;

        return $telegraph;
    }

    public function streamable(): static
    {
        $telegraph = clone $this;

        $telegraph->data['supports_streaming'] = true;

        return $telegraph;
    }
}

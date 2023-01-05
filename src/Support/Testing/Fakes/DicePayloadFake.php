<?php

namespace DefStudio\Telegraph\Support\Testing\Fakes;

use DefStudio\Telegraph\Concerns\FakesRequests;
use DefStudio\Telegraph\ScopedPayloads\DicePayload;

class DicePayloadFake extends DicePayload
{
    use FakesRequests;

    public function emoji(string $emoji): static
    {
        $telegraph = clone $this;

        $telegraph->data['emoji'] = $emoji;

        return $telegraph;
    }
}

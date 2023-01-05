<?php

namespace DefStudio\Telegraph\Support\Testing\Fakes;

use DefStudio\Telegraph\Concerns\FakesRequests;
use DefStudio\Telegraph\ScopedPayloads\ContactPayload;

class ContactPayloadFake extends ContactPayload
{
    use FakesRequests;

    public function lastName(string $lastName): static
    {
        $telegraph = clone $this;

        $telegraph->data['last_name'] = $lastName;

        return $telegraph;
    }

    public function vcard(string $vcard): static
    {
        $telegraph = clone $this;

        $telegraph->data['vcard'] = $vcard;

        return $telegraph;
    }

    public function performer(int $performer): static
    {
        $telegraph = clone $this;

        $telegraph->data['performer'] = $performer;

        return $telegraph;
    }

    public function title(int $title): static
    {
        $telegraph = clone $this;

        $telegraph->data['title'] = $title;

        return $telegraph;
    }
}

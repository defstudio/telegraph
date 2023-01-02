<?php

namespace DefStudio\Telegraph\ScopedPayloads;

use DefStudio\Telegraph\Concerns\BuildsFromTelegraphClass;
use DefStudio\Telegraph\Telegraph;

class LocationPayload extends Telegraph
{
    use BuildsFromTelegraphClass;

    public function horizontalAccuracy(float $value): static
    {
        $telegraph = clone $this;

        $telegraph->data['horizontal_accuracy'] = $value;

        return $telegraph;
    }

    public function livePeriod(int $time): static
    {
        $telegraph = clone $this;

        $telegraph->data['live_period'] = $time;

        return $telegraph;
    }

    public function heading(int $heading): static
    {
        $telegraph = clone $this;

        $telegraph->data['heading'] = $heading;

        return $telegraph;
    }

    public function proximityAlertRadius(int $value): static
    {
        $telegraph = clone $this;

        $telegraph->data['proximity_alert_radius'] = $value;

        return $telegraph;
    }
}

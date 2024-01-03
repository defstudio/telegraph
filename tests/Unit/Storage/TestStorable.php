<?php

namespace DefStudio\Telegraph\Tests\Unit\Storage;

use DefStudio\Telegraph\Concerns\HasStorage;
use DefStudio\Telegraph\Contracts\Storable;

class TestStorable implements Storable
{
    use HasStorage;

    public function storageKey(): string
    {
        return 'foo';
    }
}

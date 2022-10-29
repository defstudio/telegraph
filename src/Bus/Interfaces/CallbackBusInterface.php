<?php

namespace DefStudio\Telegraph\Bus\Interfaces;

use DefStudio\Telegraph\DTO\CallbackData;
use Illuminate\Http\Request;

interface CallbackBusInterface
{
    public function processCallback(Request $request): void;

    public function parseData(string $rawData): CallbackData;
}

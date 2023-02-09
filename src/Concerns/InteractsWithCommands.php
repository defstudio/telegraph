<?php


/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\Telegraph;

/**
 * @mixin Telegraph
 */

trait InteractsWithCommands
{
    public function getMyCommands(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_GET_BOT_COMMANDS;

        return $telegraph;
    }
}

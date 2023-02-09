<?php


/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\Exceptions\BotCommandException;
use DefStudio\Telegraph\Telegraph;

/**
 * @mixin Telegraph
 */

trait InteractsWithCommands
{
    public function getRegisteredCommands(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_GET_REGISTERED_BOT_COMMANDS;

        return $telegraph;
    }

    /**
     * @param array<string, string> $commands
     */
    public function registerBotCommands(array $commands): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_REGISTER_BOT_COMMANDS;

        if (count($commands) > 100) {
            throw BotCommandException::tooManyCommands();
        }

        $telegraph->data['commands'] = collect($commands)->map(function (string $description, string $command) {
            if (strlen($command) > 32) {
                throw BotCommandException::longCommand($command);
            }

            if (!preg_match('/[a-z0-9_]+/', $command)) {
                throw BotCommandException::invalidCommand($command);
            }

            return [
                'command' => $command,
                'description' => $description,
            ];
        })->values()->toArray();

        return $telegraph;
    }

    public function unregisterBotCommands(): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_UNREGISTER_BOT_COMMANDS;

        return $telegraph;
    }
}

<?php

namespace DefStudio\Telegraph;

final class CallbackResolver
{
    /** @var array<string, class-string<Callback>> */
    private array $callbackMap = [];

    public function __construct(array $config)
    {
        foreach ($config['bots'] as $botName => $botConfig) {
            /** @var class-string<Callback> $callback */
            foreach ($botConfig['callbacks'] as $callback) {
                $this->callbackMap[$botName][$callback::$name] = $callback;
            }
        }
    }

    public function callbackClassByName(string $botName, string $name): string|false
    {
        return $this->callbackMap[$botName][$name] ?? false ;
    }
}

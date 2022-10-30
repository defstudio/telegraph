<?php

namespace DefStudio\Telegraph;

final class CallbackResolver
{
    /** @var array<string, array<string, class-string<Callback>>> */
    private array $callbackMap = [];

    /**
     * @param array<string, array<string, array<string>>> $botsConfigs
     */
    public function __construct(array $botsConfigs)
    {
        foreach ($botsConfigs as $botName => $botConfig) {
            $this->callbackMap[$botName] = [];
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

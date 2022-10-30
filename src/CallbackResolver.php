<?php

namespace DefStudio\Telegraph;

use DefStudio\Telegraph\DTO\CallbackData;
use DefStudio\Telegraph\Exceptions\TelegramWebhookException;

final class CallbackResolver
{
    /** @var array<string, class-string<Callback>> */
    private array $callbackMap = [];
    /** @var array<string, class-string<CallbackData>> */
    private array $callbackDataMap = [];

    public function __construct(array $config)
    {
        foreach ($config['bots'] as $botName => $botConfig) {
            /** @var class-string<Callback> $callback */
            foreach ($botConfig['callbacks'] as $callback) {
                /** @var class-string<CallbackData> $dataClass */
                $dataClass = $callback::getDataClass();
                $this->callbackMap[$botName][$dataClass::name()] = $callback;
                $this->callbackDataMap[$botName][$dataClass::name()] = $dataClass;
            }
        }
    }

    /**
     * @throws TelegramWebhookException
     */
    public function callbackClassByName(string $botName, string $name): string
    {
        return $this->callbackMap[$botName][$name] ?? throw TelegramWebhookException::invalidAction($name);
    }

    /**
     * @throws TelegramWebhookException
     */
    public function toCallbackData(string $botName, string $rawData): CallbackData
    {
        if (trim($rawData) == '') {
            throw TelegramWebhookException::invalidData('callback_data is missing');
        }
        // callback_data format: `action?param=1&...`
        $exploded = explode('?', $rawData);
        if (count($exploded) !== 2) {
            throw TelegramWebhookException::invalidData('callback_data without action');
        }

        [$name, $data] = $exploded;
        if (
            is_string($name)
            && array_key_exists($name, $this->callbackDataMap[$botName] ?? [])
        ) {
            parse_str($data, $decoded);

            return new $this->callbackDataMap[$name]($decoded);
        }

        throw TelegramWebhookException::invalidAction($name);
    }
}

<?php

namespace DefStudio\Telegraph\Bus;

use DefStudio\Telegraph\Bus\Interfaces\CallbackBusInterface;
use DefStudio\Telegraph\Callback;
use DefStudio\Telegraph\DTO\CallbackData;
use DefStudio\Telegraph\DTO\CallbackQuery;
use DefStudio\Telegraph\Exceptions\TelegramWebhookException;
use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CallbackBus implements CallbackBusInterface
{
    /** @var array<string, class-string<Callback>> $callbacks */
    private array $callbackMap = [];
    /** @var array<string, class-string<CallbackData>> $callbacks */
    private array $callbackDataMap = [];

    protected CallbackQuery $callbackQuery;

    public function __construct(
        protected TelegraphBot $bot,
    ) {
        $this->fillMaps();
    }

    /**
     * @throws TelegramWebhookException
     */
    public function processCallback(Request $request): void
    {
        /* @phpstan-ignore-next-line */
        $this->callbackQuery = CallbackQuery::fromArray($request->input('callback_query'));

        $data = $this->parseData($this->callbackQuery->rawData());

        if (config('telegraph.debug_mode')) {
            Log::debug('Telegraph webhook callback', $data->toArray());
        }

        $this->callbackQuery->setData($data);

        /** @var Callback $callback */
        $callback = new $this->callbackMap[$data::name()]($this->bot, $this->callbackQuery, $request);

        $callback->handle();
    }

    /**
     * @throws TelegramWebhookException
     */
    public function parseData(string $rawData): CallbackData
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
        if (is_string($name) && array_key_exists($name, $this->callbackDataMap)) {
            parse_str($data, $decoded);
            return new $this->callbackDataMap[$name]($decoded);
        }

        throw TelegramWebhookException::invalidAction($name);
    }

    protected function fillMaps(): void
    {
        $callbacks = config('telegraph.bots.' . $this->bot->name . '.callbacks');

        /** @var class-string<Callback> $callback */
        foreach ($callbacks as $callback) {
            /** @var class-string<CallbackData> $dataClass */
            $dataClass = $callback::getDataClass();
            $this->callbackMap[$dataClass::name()] = $callback;
            $this->callbackDataMap[$dataClass::name()] = $dataClass;
        }
    }
}

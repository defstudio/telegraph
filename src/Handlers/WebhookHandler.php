<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\LaravelTelegraph\Handlers;

use DefStudio\LaravelTelegraph\Exceptions\TelegramWebhookException;
use DefStudio\LaravelTelegraph\Facades\LaravelTelegraph;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

abstract class WebhookHandler
{
    protected string $chatId;
    protected string $messageId;
    protected string $callbackQueryId;
    protected Request $request;
    protected Collection $data;
    protected Collection $originalKeyboard;


    public function handle(Request $request)
    {
        $this->request = $request;
        $this->extractData();

        $action = $this->data->get('action');

        if (!method_exists($this, $action)) {
            report(TelegramWebhookException::invalidAction($action));
            $this->reply('Invalid action');
            return;
        }

        $this->$action();
    }

    private function extractData(): void
    {
        $this->chatId = $this->request->input('callback_query.message.chat.id');
        $this->messageId = $this->request->input('callback_query.message.message_id');
        $this->callbackQueryId = $this->request->input('callback_query.id');
        $this->originalKeyboard = collect($this->request->input('callback_query.message.reply_markup.inline_keyboard', []))->flatten(1);
        $this->data = Str::of($this->request->input('callback_query.data'))->explode(';')
            ->mapWithKeys(function (string $entity) {
                $entity = explode(':', $entity);
                $key = $entity[0];
                $value = $entity[1];

                return [$key => $value];
            });
    }

    protected function reply(string $message): void
    {
        LaravelTelegraph::answerWebhook($this->callbackQueryId, $message)->send();
    }

    /**
     * @param array<array<array<non-empty-string, non-empty-string>>> $newKeyboard
     */
    protected function replaceKeyboard(array $newKeyboard): void
    {
        LaravelTelegraph::chat($this->chatId)->replaceKeyboard($this->messageId, $newKeyboard)->send();
    }

    protected function deleteKeyboard(): void
    {
        LaravelTelegraph::chat($this->chatId)->replaceKeyboard($this->messageId, [])->send();
    }
}

<?php

/** @noinspection PhpUnused */
/** @noinspection PhpDocMissingThrowsInspection */

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph;

use DefStudio\Telegraph\Client\TelegraphResponse;
use DefStudio\Telegraph\Concerns\ComposesMessages;
use DefStudio\Telegraph\Concerns\HasBotsAndChats;
use DefStudio\Telegraph\Concerns\InteractsWithTelegram;
use DefStudio\Telegraph\Concerns\InteractsWithWebhooks;
use DefStudio\Telegraph\Concerns\ManagesKeyboards;
use DefStudio\Telegraph\Contracts\TelegraphContract;
use Illuminate\Foundation\Bus\PendingDispatch;

class Telegraph
{
    use InteractsWithTelegram;
    use HasBotsAndChats;
    use ComposesMessages;
    use ManagesKeyboards;
    use InteractsWithWebhooks;

    public const PARSE_HTML = 'html';
    public const PARSE_MARKDOWN = 'markdown';

    protected const TELEGRAM_API_BASE_URL = 'https://api.telegram.org/bot';

    public const ENDPOINT_GET_BOT_UPDATES = 'getUpdates';
    public const ENDPOINT_GET_BOT_INFO = 'getMe';
    public const ENDPOINT_SET_WEBHOOK = 'setWebhook';
    public const ENDPOINT_GET_WEBHOOK_DEBUG_INFO = 'getWebhookInfo';
    public const ENDPOINT_ANSWER_WEBHOOK = 'answerCallbackQuery';
    public const ENDPOINT_REPLACE_KEYBOARD = 'editMessageReplyMarkup';
    public const ENDPOINT_MESSAGE = 'sendMessage';
    public const ENDPOINT_DELETE_MESSAGE = 'deleteMessage';
    public const ENDPOINT_EDIT_MESSAGE = 'editMessageText';

    /** @var array<string, mixed> */
    protected array $data = [];

    public function send(): TelegraphResponse
    {
        $response = $this->sendRequestToTelegram();

        return TelegraphResponse::fromResponse($response);
    }

    public function dispatch(string $queue = null): PendingDispatch
    {
        return $this->dispatchRequestToTelegram($queue);
    }
}

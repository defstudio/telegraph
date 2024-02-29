<?php

/** @noinspection PhpUnused */
/** @noinspection PhpDocMissingThrowsInspection */

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph;

use DefStudio\Telegraph\Client\TelegraphResponse;
use DefStudio\Telegraph\Concerns\AnswersInlineQueries;
use DefStudio\Telegraph\Concerns\CallTraitsMethods;
use DefStudio\Telegraph\Concerns\ComposesMessages;
use DefStudio\Telegraph\Concerns\CreatesScopedPayloads;
use DefStudio\Telegraph\Concerns\HasBotsAndChats;
use DefStudio\Telegraph\Concerns\InteractsWithCommands;
use DefStudio\Telegraph\Concerns\InteractsWithTelegram;
use DefStudio\Telegraph\Concerns\InteractsWithWebhooks;
use DefStudio\Telegraph\Concerns\InteractWithUsers;
use DefStudio\Telegraph\Concerns\ManagesKeyboards;
use DefStudio\Telegraph\Concerns\SendsAttachments;
use DefStudio\Telegraph\Concerns\StoresFiles;
use DefStudio\Telegraph\DTO\Attachment;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Support\Collection;

class Telegraph
{
    use CallTraitsMethods;
    use InteractsWithTelegram;
    use HasBotsAndChats;
    use ComposesMessages;
    use ManagesKeyboards;
    use InteractsWithWebhooks;
    use SendsAttachments;
    use StoresFiles;
    use AnswersInlineQueries;
    use CreatesScopedPayloads;
    use InteractWithUsers;
    use InteractsWithCommands;


    public const PARSE_HTML = 'html';
    public const PARSE_MARKDOWN = 'markdown';
    public const PARSE_MARKDOWNV2 = 'MarkdownV2';

    protected const TELEGRAM_API_BASE_URL = 'https://api.telegram.org/bot';
    protected const TELEGRAM_API_FILE_BASE_URL = 'https://api.telegram.org/file/bot';

    public const ENDPOINT_GET_BOT_UPDATES = 'getUpdates';
    public const ENDPOINT_GET_BOT_INFO = 'getMe';
    public const ENDPOINT_REGISTER_BOT_COMMANDS = 'setMyCommands';
    public const ENDPOINT_GET_REGISTERED_BOT_COMMANDS = 'getMyCommands';
    public const ENDPOINT_UNREGISTER_BOT_COMMANDS = 'deleteMyCommands';
    public const ENDPOINT_SET_WEBHOOK = 'setWebhook';
    public const ENDPOINT_UNSET_WEBHOOK = 'deleteWebhook';
    public const ENDPOINT_GET_WEBHOOK_DEBUG_INFO = 'getWebhookInfo';
    public const ENDPOINT_ANSWER_WEBHOOK = 'answerCallbackQuery';
    public const ENDPOINT_REPLACE_KEYBOARD = 'editMessageReplyMarkup';
    public const ENDPOINT_MESSAGE = 'sendMessage';
    public const ENDPOINT_DELETE_MESSAGE = 'deleteMessage';
    public const ENDPOINT_PIN_MESSAGE = 'pinChatMessage';
    public const ENDPOINT_UNPIN_MESSAGE = 'unpinChatMessage';
    public const ENDPOINT_UNPIN_ALL_MESSAGES = 'unpinAllChatMessages';
    public const ENDPOINT_EDIT_MESSAGE = 'editMessageText';
    public const ENDPOINT_EDIT_CAPTION = 'editMessageCaption';
    public const ENDPOINT_EDIT_MEDIA = 'editMessageMedia';
    public const ENDPOINT_SEND_LOCATION = 'sendLocation';
    public const ENDPOINT_SEND_ANIMATION = 'sendAnimation';
    public const ENDPOINT_SEND_VOICE = 'sendVoice';
    public const ENDPOINT_SEND_CHAT_ACTION = 'sendChatAction';
    public const ENDPOINT_SEND_DOCUMENT = 'sendDocument';
    public const ENDPOINT_SEND_PHOTO = 'sendPhoto';
    public const ENDPOINT_SEND_VIDEO = 'sendVideo';
    public const ENDPOINT_SEND_AUDIO = 'sendAudio';
    public const ENDPOINT_SEND_CONTACT = 'sendContact';
    public const ENDPOINT_GET_FILE = 'getFile';
    public const ENDPOINT_ANSWER_INLINE_QUERY = 'answerInlineQuery';
    public const ENDPOINT_SET_CHAT_TITLE = 'setChatTitle';
    public const ENDPOINT_SET_CHAT_DESCRIPTION = 'setChatDescription';
    public const ENDPOINT_SET_CHAT_PHOTO = 'setChatPhoto';
    public const ENDPOINT_DELETE_CHAT_PHOTO = 'deleteChatPhoto';
    public const ENDPOINT_EXPORT_CHAT_INVITE_LINK = 'exportChatInviteLink';
    public const ENDPOINT_CREATE_CHAT_INVITE_LINK = 'createChatInviteLink';
    public const ENDPOINT_EDIT_CHAT_INVITE_LINK = 'editChatInviteLink';
    public const ENDPOINT_REVOKE_CHAT_INVITE_LINK = 'revokeChatInviteLink';
    public const ENDPOINT_LEAVE_CHAT = 'leaveChat';
    public const ENDPOINT_GET_CHAT_INFO = 'getChat';
    public const ENDPOINT_GET_CHAT_MEMBER_COUNT = 'getChatMemberCount';
    public const ENDPOINT_GET_CHAT_MEMBER = 'getChatMember';
    public const ENDPOINT_SET_CHAT_PERMISSIONS = 'setChatPermissions';
    public const ENDPOINT_BAN_CHAT_MEMBER = 'banChatMember';
    public const ENDPOINT_UNBAN_CHAT_MEMBER = 'unbanChatMember';
    public const ENDPOINT_RESTRICT_CHAT_MEMBER = 'restrictChatMember';
    public const ENDPOINT_PROMOTE_CHAT_MEMBER = 'promoteChatMember';
    public const ENDPOINT_SEND_POLL = 'sendPoll';
    public const ENDPOINT_FORWARD_MESSAGE = 'forwardMessage';
    public const ENDPOINT_COPY_MESSAGE = 'copyMessage';
    public const ENDPOINT_GET_USER_PROFILE_PHOTOS = 'getUserProfilePhotos';
    public const ENDPOINT_SET_CHAT_MENU_BUTTON = 'setChatMenuButton';
    public const ENDPOINT_GET_CHAT_MENU_BUTTON = 'getChatMenuButton';
    public const ENDPOINT_DICE = 'sendDice';


    /** @var array<string, mixed> */
    protected array $data = [];

    /** @var Collection<string, Attachment> */
    protected Collection $files;

    public function __construct()
    {
        $this->files = Collection::empty();
    }

    /**
     * @param callable(Telegraph $keyboard): Telegraph $callback
     */
    public function when(bool $condition, callable $callback): Telegraph
    {
        if ($condition) {
            return $callback($this);
        }

        return $this;
    }

    public function send(): TelegraphResponse
    {
        $response = $this->sendRequestToTelegram();

        return TelegraphResponse::fromResponse($response);
    }

    public function dispatch(string $queue = null): PendingDispatch
    {
        return $this->dispatchRequestToTelegram($queue);
    }

    /**
     * @return never-returns
     */
    public function dd(): void
    {
        dd($this->toArray());
    }

    public function dump(): Telegraph
    {
        dump($this->toArray());

        return $this;
    }

    public function withData(string $key, mixed $value): static
    {
        $telegraph = clone $this;

        data_set($telegraph->data, $key, $value);

        return $telegraph;
    }
}

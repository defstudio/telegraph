<?php

namespace DefStudio\Telegraph\Concerns;

trait HasEndpoints
{
    public const ENDPOINT_GET_BOT_UPDATES = 'getUpdates';
    public const ENDPOINT_GET_BOT_INFO = 'getMe';
    public const ENDPOINT_REGISTER_BOT_COMMANDS = 'setMyCommands';
    public const ENDPOINT_GET_REGISTERED_BOT_COMMANDS = 'getMyCommands';
    public const ENDPOINT_UNREGISTER_BOT_COMMANDS = 'deleteMyCommands';
    public const ENDPOINT_SET_WEBHOOK = 'setWebhook';
    public const ENDPOINT_UNSET_WEBHOOK = 'deleteWebhook';
    public const ENDPOINT_GET_WEBHOOK_DEBUG_INFO = 'getWebhookInfo';
    public const ENDPOINT_ANSWER_WEBHOOK = 'answerCallbackQuery';

    public const ENDPOINT_ANSWER_PRE_CHECKOUT_QUERY = 'answerPreCheckoutQuery';
    public const ENDPOINT_REPLACE_KEYBOARD = 'editMessageReplyMarkup';
    public const ENDPOINT_MESSAGE = 'sendMessage';
    public const ENDPOINT_DELETE_MESSAGE = 'deleteMessage';
    public const ENDPOINT_DELETE_MESSAGES = 'deleteMessages';
    public const ENDPOINT_READ_BUSINESS_MESSAGE = 'readBusinessMessage';
    public const ENDPOINT_DELETE_BUSINESS_MESSAGES = 'deleteBusinessMessages';
    public const ENDPOINT_PIN_MESSAGE = 'pinChatMessage';
    public const ENDPOINT_UNPIN_MESSAGE = 'unpinChatMessage';
    public const ENDPOINT_UNPIN_ALL_MESSAGES = 'unpinAllChatMessages';
    public const ENDPOINT_EDIT_MESSAGE = 'editMessageText';
    public const ENDPOINT_EDIT_CAPTION = 'editMessageCaption';
    public const ENDPOINT_EDIT_MEDIA = 'editMessageMedia';
    public const ENDPOINT_SEND_LOCATION = 'sendLocation';
    public const ENDPOINT_SEND_ANIMATION = 'sendAnimation';
    public const ENDPOINT_SEND_VOICE = 'sendVoice';
    public const ENDPOINT_SEND_MEDIA_GROUP = 'sendMediaGroup';
    public const ENDPOINT_SEND_CHAT_ACTION = 'sendChatAction';
    public const ENDPOINT_SEND_DOCUMENT = 'sendDocument';
    public const ENDPOINT_SEND_PHOTO = 'sendPhoto';
    public const ENDPOINT_SEND_VIDEO = 'sendVideo';
    public const ENDPOINT_SEND_VIDEO_NOTE = 'sendVideoNote';
    public const ENDPOINT_SEND_AUDIO = 'sendAudio';
    public const ENDPOINT_SEND_CONTACT = 'sendContact';
    public const ENDPOINT_GET_FILE = 'getFile';
    public const ENDPOINT_ANSWER_INLINE_QUERY = 'answerInlineQuery';
    public const ENDPOINT_SET_CHAT_TITLE = 'setChatTitle';
    public const ENDPOINT_SET_CHAT_DESCRIPTION = 'setChatDescription';
    public const ENDPOINT_SET_CHAT_PHOTO = 'setChatPhoto';
    public const ENDPOINT_SET_MESSAGE_REACTION = 'setMessageReaction';
    public const ENDPOINT_DELETE_CHAT_PHOTO = 'deleteChatPhoto';
    public const ENDPOINT_EXPORT_CHAT_INVITE_LINK = 'exportChatInviteLink';
    public const ENDPOINT_CREATE_CHAT_INVITE_LINK = 'createChatInviteLink';
    public const ENDPOINT_CREATE_FORUM_TOPIC = 'createForumTopic';
    public const ENDPOINT_EDIT_FORUM_TOPIC = 'editForumTopic';
    public const ENDPOINT_CLOSE_FORUM_TOPIC = 'closeForumTopic';
    public const ENDPOINT_REOPEN_FORUM_TOPIC = 'reopenForumTopic';
    public const ENDPOINT_DELETE_FORUM_TOPIC = 'deleteForumTopic';
    public const ENDPOINT_EDIT_CHAT_INVITE_LINK = 'editChatInviteLink';
    public const ENDPOINT_REVOKE_CHAT_INVITE_LINK = 'revokeChatInviteLink';
    public const ENDPOINT_LEAVE_CHAT = 'leaveChat';
    public const ENDPOINT_GET_CHAT_INFO = 'getChat';
    public const ENDPOINT_GET_CHAT_MEMBER_COUNT = 'getChatMemberCount';
    public const ENDPOINT_GET_CHAT_MEMBER = 'getChatMember';
    public const ENDPOINT_GET_CHAT_ADMINISTRATORS = 'getChatAdministrators';
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
    public const ENDPOINT_SEND_STICKER = 'sendSticker';
    public const ENDPOINT_SEND_VENUE = 'sendVenue';

    public const ENDPOINT_APPROVE_CHAT_JOIN_REQUEST = 'approveChatJoinRequest';
    public const ENDPOINT_DECLINE_CHAT_JOIN_REQUEST = 'declineChatJoinRequest';

    public const ENDPOINT_SEND_INVOICE = 'sendInvoice';
    public const ENDPOINT_CREATE_INVOICE_LINK = 'createInvoiceLink';
    public const ENDPOINT_REFUND_STAR_PAYMENT = 'refundStarPayment';

    public const ENDPOINT_SEND_GAME = 'sendGame';

}

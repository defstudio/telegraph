---
title: 'Incoming Data'
description: ''
category: 'Webhooks'
fullscreen: false 
position: 68
---

Data obtained from manual polling or webhooks is available through a set of DTO:

## `DefStudio\Telegraph\DTO\TelegramUpdate`

contains incoming data (a message or a callback query)

- `->id()` incoming _update_id_
- `->message()` (optional) an instance of [`DefStudio\Telegraph\DTO\Message`](webhooks/dto#defstudio-telegraph-dto-message) 
- `->callbackQuery()` (optional) an instance of [`DefStudio\Telegraph\DTO\CallbackQuery`](webhooks/dto#defstudio-telegraph-dto-callback-query)


## `DefStudio\Telegraph\DTO\Message`

- `->id()` incoming _message_id_
- `->date()` a `CarbonInterface` holding the message sent date
- `->editDate()` a `CarbonInterface` holding the message last edit date
- `->text()` the message text
- `->protected()` a boolean flag that states the message is protected from forwarding and download
- `->from()` (optional) an instance of [`DefStudio\Telegraph\DTO\User`](webhooks/dto#defstudio-telegraph-dto-user) holding data about the message's sender
- `->forwardedFrom()` (optional) an instance of [`DefStudio\Telegraph\DTO\User`](webhooks/dto#defstudio-telegraph-dto-user) holding data about a forwarded message's original sender
- `->chat()` (optional) an instance of [`DefStudio\Telegraph\DTO\Chat`](webhooks/dto#defstudio-telegraph-dto-chat) holding data about the chat to which the message belongs to 
- `->keyboard()` (optional) an instance of [`DefStudio\Telegraph\Keyboard\Keyboard`](feature/keyboards) holding the message inline keyboard
- `->replyToMessage()` (optional) an instance of the original [`DefStudio\Telegraph\DTO\Message`](webhooks/dto#defstudio-telegraph-dto-message) that the current message is replying 
- `->photos()` (optional) a collection of [`DefStudio\Telegraph\DTO\Photo`](webhooks/dto#defstudio-telegraph-dto-photo) holding data about the contained image resolutions
- `->animation()` (optional) an instance of [`DefStudio\Telegraph\DTO\Photo`](webhooks/dto#defstudio-telegraph-dto-animation) holding data about the contained animation
- `->audio()` (optional) an instance of [`DefStudio\Telegraph\DTO\Audio`](webhooks/dto#defstudio-telegraph-dto-audio) holding data about the contained audio
- `->document()` (optional) an instance of [`DefStudio\Telegraph\DTO\Document`](webhooks/dto#defstudio-telegraph-dto-document) holding data about the contained document
- `->video()` (optional) an instance of [`DefStudio\Telegraph\DTO\Video`](webhooks/dto#defstudio-telegraph-dto-video) holding data about the contained video
- `->location()` (optional) an instance of [`DefStudio\Telegraph\DTO\Location`](webhooks/dto#defstudio-telegraph-dto-location) holding data about the contained location
- `->contact()` (optional) an instance of [`DefStudio\Telegraph\DTO\Contact`](webhooks/dto#defstudio-telegraph-dto-contact) holding data about the contained contact data
- `->voice()` (optional) an instance of [`DefStudio\Telegraph\DTO\Voice`](webhooks/dto#defstudio-telegraph-dto-voice) holding data about the contained voical message
- `->newChatMembers()` a collection of [`DefStudio\Telegraph\DTO\User`](webhooks/dto#defstudio-telegraph-dto-user) holding the list of users that joined the group/supergroup
- `->leftChatMember()` (optional) an instance of [`DefStudio\Telegraph\DTO\User`](webhooks/dto#defstudio-telegraph-dto-user) holding data about the user that left the group/supergroup




## `DefStudio\Telegraph\DTO\CallbackQuery`

- `->id()` incoming _callback_query_id_
- `->from()` (optional) an instance of the [`DefStudio\Telegraph\DTO\User`](webhooks/dto#defstudio-telegraph-dto-user) that triggered the callback query
- `->message()` (optional) an instance of the [`DefStudio\Telegraph\DTO\Message`](webhooks/dto#defstudio-telegraph-dto-message) that triggered the callback query
- `->data()` an `Illuminate\Support\Collection` that holds the key/value pairs of the callback query data


## `DefStudio\Telegraph\DTO\User`

- `->id()` user ID
- `->isBot()` marks if the user is a bot or a real user
- `->firstName()` user's first name 
- `->lastName()` user's last name 
- `->userName()` user's username 

## `DefStudio\Telegraph\DTO\Audio`

- `->id()` file ID
- `->duration()` audio duration
- `->title()` (optional) audio title
- `->filename()` (optional) audio file name
- `->mimeType()` (optional) audio MIME type
- `->filesize()` (optional) audio file size in Bytes
- `->thumbnail()` (optional) an instance of the [`DefStudio\Telegraph\DTO\Photo`](webhooks/dto#defstudio-telegraph-dto-photo) that holds data about the thumbnail

## `DefStudio\Telegraph\DTO\Animation`

- `->id()` file ID
- `->width()` animation width
- `->height()` animation height
- `->duration()` animation duration
- `->filename()` (optional) animation file name
- `->mimeType()` (optional) animation MIME type
- `->filesize()` (optional) animation file size in Bytes
- `->thumbnail()` (optional) an instance of the [`DefStudio\Telegraph\DTO\Photo`](webhooks/dto#defstudio-telegraph-dto-photo) that holds data about the thumbnail

## `DefStudio\Telegraph\DTO\Document`

- `->id()` file ID
- `->filename()` (optional) document file name
- `->mimeType()` (optional) document MIME type
- `->filesize()` (optional) document file size in Bytes
- `->thumbnail()` (optional) an instance of the [`DefStudio\Telegraph\DTO\Photo`](webhooks/dto#defstudio-telegraph-dto-photo) that holds data about the thumbnail

## `DefStudio\Telegraph\DTO\Photo`

- `->id()` file ID
- `->width()` photo width
- `->height()` photo height
- `->filesize()` (optional) photo file size in Bytes

## `DefStudio\Telegraph\DTO\Video`

- `->id()` file ID
- `->width()` video width
- `->height()` video height
- `->duration()` video duration
- `->filename()` (optional) video file name
- `->mimeType()` (optional) video MIME type
- `->filesize()` (optional) video file size in Bytes
- `->thumbnail()` (optional) an instance of the [`DefStudio\Telegraph\DTO\Photo`](webhooks/dto#defstudio-telegraph-dto-photo) that holds data about the thumbnail

## `DefStudio\Telegraph\DTO\Location`

- `->latitude()` location latitude
- `->longitude()` location longitude
- `->accuracy()` (optional) location horizontal accuracy

## `DefStudio\Telegraph\DTO\Contact`

- `->phoneNumber()` contact's phone number
- `->firstName()` contact's first name
- `->lastName()` (optional) contact's last name
- `->userId()` (optional) contact's telegram user ID
- `->vcard()` (optional) contact's [vCard](https://en.wikipedia.org/wiki/VCard)

## `DefStudio\Telegraph\DTO\Voice`

- `->id()` file ID
- `->duration()` audio duration
- `->mimeType()` (optional) audio MIME type
- `->filesize()` (optional) audio file size in Bytes



## `DefStudio\Telegraph\DTO\InlineQuery`

- `->id()` inline query _id_
- `->query()` the query typed by the user after the bot's name
- `->from()` an instance of [`DefStudio\Telegraph\DTO\User`](webhooks/dto#defstudio-telegraph-dto-user) holding data about the user that started the query
- `->offset()` offset of the results to be returned, can be controlled by the bot
- `->chat_type()` type of the chat, from which the inline query was sent. Can be either “sender” for a private chat with the inline query sender, “private”, “group”, “supergroup”, or “channel”. The chat type should be always known for requests sent from official clients and most third-party clients, unless the request was sent from a secret chat
- `->location()` (optional) an instance of [`DefStudio\Telegraph\DTO\Location`](webhooks/dto#defstudio-telegraph-dto-photo) containing sender location, only for bots that request user location.

## `DefStudio\Telegraph\DTO\InlineQueryResultGif`

This is a DTO for outgoing data, wraps info about the Gif result returned to the user

## `DefStudio\Telegraph\DTO\InlineQueryResultPhoto`

This is a DTO for outgoing data, wraps info about the Photo result returned to the user

## `DefStudio\Telegraph\DTO\InlineQueryResultContact`

This is a DTO for outgoing data, wraps info about the Contact result returned to the user

## `DefStudio\Telegraph\DTO\InlineQueryResultArticle`

This is a DTO for outgoing data, wraps info about the Article result returned to the user

## `DefStudio\Telegraph\DTO\InlineQueryResultMpeg4Gif`

This is a DTO for outgoing data, wraps info about the Mpeg4Gif result returned to the user

## `DefStudio\Telegraph\DTO\InlineQueryResultVideo`

This is a DTO for outgoing data, wraps info about the Video result returned to the user

## `DefStudio\Telegraph\DTO\InlineQueryResultAudio`

This is a DTO for outgoing data, wraps info about the Audio result returned to the user

## `DefStudio\Telegraph\DTO\InlineQueryResultVoice`

This is a DTO for outgoing data, wraps info about the Voice result returned to the user

## `DefStudio\Telegraph\DTO\InlineQueryResultDocument`

This is a DTO for outgoing data, wraps info about the Document result returned to the user

## `DefStudio\Telegraph\DTO\InlineQueryResultLocation`

This is a DTO for outgoing data, wraps info about the Location result returned to the user

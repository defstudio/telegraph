<?php

/** @noinspection PhpUnhandledExceptionInspection */

use DefStudio\Telegraph\DTO\ChatMember;
use Illuminate\Support\Str;

it('export all properties to array', function () {
    $dto = ChatMember::fromArray([
        'user' => [
            'id' => 2222222,
            'is_bot' => true,
            'first_name' => 'Bot',
            'username' => 'MarioBot',
        ],
        'status' => 'kicked',
        'until_date' => 0,
        'custom_title' => 'My custom title',
        'can_be_edited' => true,
        'can_change_info' => true,
        'can_invite_users' => true,
        'can_manage_chat' => true,
        'can_manage_topics' => true,
        'can_manage_video_chats' => true,
        'can_manage_voice_chats' => true,
        'can_manage_direct_messages' => true,
        'can_restrict_members' => true,
        'can_promote_members' => true,
        'can_post_messages' => true,
        'can_edit_messages' => true,
        'can_delete_messages' => true,
        'can_pin_messages' => true,
        'can_post_stories' => true,
        'can_edit_stories' => true,
        'can_delete_stories' => true,
        'can_send_messages' => true,
        'can_send_media_messages' => true,
        'can_send_audios' => true,
        'can_send_documents' => true,
        'can_send_photos' => true,
        'can_send_videos' => true,
        'can_send_video_notes' => true,
        'can_send_voice_notes' => true,
        'can_send_polls' => true,
        'can_send_other_messages' => true,
        'can_add_web_page_previews' => true,
    ]);

    $array = $dto->toArray();

    $reflection = new ReflectionClass($dto);
    foreach ($reflection->getProperties() as $property) {
        expect($array)->toHaveKey(Str::of($property->name)->snake());
    }
});

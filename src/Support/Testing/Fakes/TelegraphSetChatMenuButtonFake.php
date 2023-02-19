<?php

/** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection PhpUnused */

/** @noinspection PhpPropertyOnlyWrittenInspection */

namespace DefStudio\Telegraph\Support\Testing\Fakes;

use DefStudio\Telegraph\Concerns\FakesRequests;

use DefStudio\Telegraph\ScopedPayloads\SetChatMenuButtonPayload;
use DefStudio\Telegraph\Telegraph;

class TelegraphSetChatMenuButtonFake extends SetChatMenuButtonPayload
{
    use FakesRequests;

    /**
     * @param array<string, array<mixed>> $replies
     */
    public function __construct(array $replies = [])
    {
        parent::__construct();
        $this->replies = $replies;
    }

    /**
     * @param array<string, mixed> $options
     */
    public static function assertChangedMenuButton(string $type, array $options = []): void
    {
        self::assertSentData(Telegraph::ENDPOINT_SET_CHAT_MENU_BUTTON, [
            'menu_button' => [
                'type' => $type,
            ] + $options,
        ]);
    }
}

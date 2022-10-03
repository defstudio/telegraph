<?php

/** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection PhpUnused */

/** @noinspection PhpPropertyOnlyWrittenInspection */

namespace DefStudio\Telegraph\Support\Testing\Fakes;

use DefStudio\Telegraph\Concerns\FakesRequests;

use DefStudio\Telegraph\ScopedPayloads\TelegraphPollPayload;
use DefStudio\Telegraph\Telegraph;

class TelegraphPollFake extends TelegraphPollPayload
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
     * @param array<int, string> $options
     */
    public static function assertSentPoll(string $question, array $options = []): void
    {
        if (empty($options)) {
            self::assertSentData(Telegraph::ENDPOINT_SEND_POLL, [
                'question' => $question,
            ], false);

            return;
        }

        self::assertSentData(Telegraph::ENDPOINT_SEND_POLL, [
            'question' => $question,
            'options' => ['bar!', 'baz!'],
        ]);
    }
}

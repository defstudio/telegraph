<?php

/** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection PhpUnused */

/** @noinspection PhpPropertyOnlyWrittenInspection */

namespace DefStudio\Telegraph\Support\Testing\Fakes;

use DefStudio\Telegraph\Concerns\FakesRequests;
use DefStudio\Telegraph\ScopedPayloads\TelegraphQuizPayload;
use DefStudio\Telegraph\Telegraph;

class TelegraphQuizFake extends TelegraphQuizPayload
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
    public static function assertSentQuiz(string $question, array $options = [], int $correct_index = null): void
    {
        $data = ['question' => $question, 'type' => 'quiz'];

        if (!empty($options)) {
            $data['options'] = $options;
        }

        if ($correct_index !== null) {
            $data['correct_option_id'] = $correct_index;
        }

        self::assertSentData(Telegraph::ENDPOINT_SEND_POLL, $data);
    }
}

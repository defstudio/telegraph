<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\DTO\InlineQueryResult;
use DefStudio\Telegraph\Exceptions\InlineQueryException;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Support\Str;

/**
 * @mixin Telegraph
 */
trait AnswersInlineQueries
{
    /**
     * @param InlineQueryResult[] $results
     */
    public function answerInlineQuery(string $inlineQueryID, array $results): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_ANSWER_INLINE_QUERY;
        $this->data = [
            'inline_query_id' => $inlineQueryID,
            'results' => collect($results)->map(fn (InlineQueryResult $result) => $result->toArray()),
        ];

        return $telegraph;
    }

    public function cache(int $seconds): Telegraph
    {
        $telegraph = clone $this;
        $telegraph->data['cache_time'] = $seconds;
        return $telegraph;
    }

    public function personal(): Telegraph
    {
        $telegraph = clone $this;
        $telegraph->data['is_personal'] = true;
        return $telegraph;
    }

    public function nextOffset(string $offset): Telegraph
    {
        $telegraph = clone $this;
        $telegraph->data['next_offset'] = $offset;
        return $telegraph;
    }

    public function offertToSwitchToPrivateMessage(string $text, string $parameter): Telegraph
    {
        if (!preg_match("#^[a-zA-Z\d_-]+$#", $parameter)) {
            throw InlineQueryException::invalidSwitchToPmParameter($parameter);
        }

        $telegraph = clone $this;
        $telegraph->data['switch_pm_text'] = $text;
        $telegraph->data['switch_pm_parameter'] = $parameter;
        return $telegraph;
    }
}

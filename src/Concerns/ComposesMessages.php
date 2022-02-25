<?php

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\Telegraph;

/**
 * @mixin Telegraph
 */
trait ComposesMessages
{
    protected string $message;
    protected string $parseMode;

    public function message(string $message): Telegraph
    {
        return match (config('telegraph.default_parse_mode')) {
            self::PARSE_MARKDOWN => $this->markdown($message),
            default => $this->html($message)
        };
    }

    public function html(string $message): Telegraph
    {
        $this->message = $message;
        $this->parseMode = 'html';

        return $this;
    }

    public function markdown(string $message): Telegraph
    {
        $this->message = $message;
        $this->parseMode = 'markdown';

        return $this;
    }
}

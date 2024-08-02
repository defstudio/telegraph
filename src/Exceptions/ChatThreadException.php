<?php

namespace DefStudio\Telegraph\Exceptions;

use Exception;

class ChatThreadException extends Exception
{
    public static function emptyThreadId(): self
    {
        return new self('Telegram Chat message_thread_id cannot be empty.');
    }
}

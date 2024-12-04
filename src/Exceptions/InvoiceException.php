<?php

namespace DefStudio\Telegraph\Exceptions;

use Exception;
use Illuminate\Support\MessageBag;

class InvoiceException extends Exception
{
    public static function validationError(MessageBag $messages): InvoiceException
    {
        return new self('Invalid Invoice: ' . $messages->toJson());
    }
}

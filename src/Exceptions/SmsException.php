<?php

namespace Sureshhemal\SmsSriLanka\Exceptions;

use Exception;

abstract class SmsException extends Exception
{
    /**
     * Create a new SMS exception instance.
     */
    public function __construct(string $message = '', int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

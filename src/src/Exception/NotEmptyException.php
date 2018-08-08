<?php

namespace App\Exception;

use Throwable;

class NotEmptyException extends APIException
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
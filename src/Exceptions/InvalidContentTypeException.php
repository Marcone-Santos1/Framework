<?php

namespace MiniRest\Framework\Exceptions;

use MiniRest\Framework\Helpers\StatusCode\StatusCode;

class InvalidContentTypeException extends \Exception
{
    public function __construct(string $message = "", int $code = StatusCode::SERVER_ERROR)
    {
        parent::__construct($message, $code);
    }
}
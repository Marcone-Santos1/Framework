<?php

namespace MiniRest\Framework\Exceptions;

use MiniRest\Framework\Helpers\StatusCode\StatusCode;

class PortifolioPrestadorNotFoundException extends \Exception
{
    public function __construct(string $message = 'o prestador não possui portifólios')
    {
        parent::__construct($message, StatusCode::NOT_FOUND);
    }
}
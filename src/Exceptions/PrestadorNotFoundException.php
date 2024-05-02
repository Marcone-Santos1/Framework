<?php

namespace MiniRestFramework\Exceptions;

use MiniRestFramework\Helpers\StatusCode\StatusCode;

class PrestadorNotFoundException extends \Exception
{
    public function __construct(string $message = 'Prestador não existe')
    {
        parent::__construct($message, StatusCode::NOT_FOUND);
    }
}
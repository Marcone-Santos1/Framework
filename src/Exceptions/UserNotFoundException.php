<?php

namespace MiniRestFramework\Exceptions;

use MiniRestFramework\Helpers\StatusCode\StatusCode;

class UserNotFoundException extends \Exception
{
    public function __construct(string $message = 'User not found')
    {
        parent::__construct($message, StatusCode::ACCESS_NOT_ALLOWED);
    }
}
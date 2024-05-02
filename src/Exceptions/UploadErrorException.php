<?php

namespace MiniRestFramework\Exceptions;

use MiniRestFramework\Helpers\StatusCode\StatusCode;

class UploadErrorException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message, StatusCode::REQUEST_ERROR);
    }
}
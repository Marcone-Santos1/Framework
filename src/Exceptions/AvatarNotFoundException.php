<?php

namespace MiniRest\Framework\Exceptions;

use MiniRest\Framework\Helpers\StatusCode\StatusCode;

class AvatarNotFoundException extends \Exception
{
    public function __construct(string $message = "Avatar não encontrado na base de dados")
    {
        parent::__construct($message, StatusCode::NOT_FOUND);
    }
}
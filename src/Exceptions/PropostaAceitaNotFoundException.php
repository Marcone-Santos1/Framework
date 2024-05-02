<?php

namespace MiniRestFramework\Exceptions;

use MiniRestFramework\Helpers\StatusCode\StatusCode;

class PropostaAceitaNotFoundException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message, StatusCode::NOT_FOUND);
    }
}

?>

<?php
namespace MiniRest\Framework\Exceptions;

use MiniRest\Framework\Helpers\StatusCode\StatusCode;


class PropostaNotFoundException extends \Exception
{
    
    public function __construct(string $message, $statusCode = StatusCode::NOT_FOUND)
    {
        parent::__construct($message, $statusCode);
    }
}

?>
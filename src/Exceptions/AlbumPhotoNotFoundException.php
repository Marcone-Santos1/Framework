<?php

namespace MiniRest\Framework\Exceptions;

use MiniRest\Framework\Helpers\StatusCode\StatusCode;

class AlbumPhotoNotFoundException extends \Exception
{
    public function __construct(string $message = 'A foto não existe, ou você não possue acesso para deletar a foto')
    {
        parent::__construct($message, StatusCode::NOT_FOUND);
    }
}
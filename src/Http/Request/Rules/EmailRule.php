<?php

namespace MiniRestFramework\Http\Request\Rules;

use MiniRestFramework\Http\Request\RequestValidation\ValidationRule;

class EmailRule implements ValidationRule
{
    public function passes($value, $params): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function errorMessage($field, $params): string
    {
        return "O campo {$field} deve ser um endereço de e-mail válido.";
    }
}
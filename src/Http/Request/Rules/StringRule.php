<?php

namespace MiniRestFramework\Http\Request\Rules;

use MiniRestFramework\Http\Request\RequestValidation\ValidationRule;

class StringRule implements ValidationRule
{

    public function passes($value, $params): bool
    {
        return is_string($value) !== false;
    }

    public function errorMessage($field, $params): string
    {
        return "O campo {$field} deve ser uma String válida.";
    }
}
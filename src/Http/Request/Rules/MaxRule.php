<?php

namespace MiniRest\Framework\Http\Request\Rules;

use MiniRest\Framework\Http\Request\RequestValidation\ValidationRule;

class MaxRule implements ValidationRule
{

    public function passes($value, $params): bool
    {
        $maxLength = intval($params[0] ?? 0);
        return strlen($value) <= $maxLength;
    }

    public function errorMessage($field, $params): string
    {
        $maxLength = intval($params[0] ?? 0);
        return "O campo {$field} não deve exceder {$maxLength} caracteres.";

    }
}
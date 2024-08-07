<?php

namespace MiniRestFramework\Http\Request\Rules;

use MiniRestFramework\Http\Request\RequestValidation\ValidationRule;

class MaxNumberRule implements ValidationRule
{

    public function passes($value, $params): bool
    {
        $maxLength = intval($params[0] ?? 0);
        return $value <= $maxLength;
    }

    public function errorMessage($field, $params): string
    {
        $maxLength = intval($params[0] ?? 0);
        return "O campo {$field} não deve exceder {$maxLength} caracteres.";

    }
}
<?php

namespace MiniRestFramework\Http\Request\Rules;

use MiniRestFramework\Http\Request\RequestValidation\ValidationRule;

class MinNumberRule implements ValidationRule
{

    public function passes($value, $params): bool
    {
        $minLength = intval($params[0] ?? 0);
        return $value >= $minLength;
    }

    public function errorMessage($field, $params): string
    {
        $minLength = intval($params[0] ?? 0);
        return "O campo {$field} deve ter pelo menos {$minLength} caracteres.";
    }
}
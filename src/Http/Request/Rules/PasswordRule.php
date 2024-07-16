<?php

namespace MiniRestFramework\Http\Request\Rules;

use MiniRestFramework\Http\Request\RequestValidation\ValidationRule;

class PasswordRule implements ValidationRule
{

    public function passes($value, $params): bool
    {
        $minLength = $this->getParam($params, 'min_length', 8);
        $requireUpper = $this->getParam($params, 'require_upper', true);
        $requireLower = $this->getParam($params, 'require_lower', true);
        $requireNumber = $this->getParam($params, 'require_number', true);
        $requireSpecial = $this->getParam($params, 'require_special', true);

        $hasMinLength = strlen($value) >= $minLength;
        $hasUpper = !$requireUpper || preg_match('/[A-Z]/', $value);
        $hasLower = !$requireLower || preg_match('/[a-z]/', $value);
        $hasNumber = !$requireNumber || preg_match('/\d/', $value);
        $hasSpecial = !$requireSpecial || preg_match('/[!@#$%^&*(),.?":{}|<>]/', $value);

        return $hasMinLength && $hasUpper && $hasLower && $hasNumber && $hasSpecial;
    }

    private function getParam($params, $key, $default)
    {
        foreach ($params as $param) {
            list($paramKey, $paramValue) = explode('=', $param);
            if ($paramKey === $key) {
                return is_bool($default) ? filter_var($paramValue, FILTER_VALIDATE_BOOLEAN) : $paramValue;
            }
        }
        return $default;
    }

    public function errorMessage($field, $params): string
    {
        return "O campo {$field} deve atender aos requisitos mínimos de senha.";
    }
}
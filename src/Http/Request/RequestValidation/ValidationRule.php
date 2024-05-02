<?php

namespace MiniRestFramework\Http\Request\RequestValidation;

interface ValidationRule
{
    public function passes($value, $params): bool;
    public function errorMessage($field, $params): string;
}
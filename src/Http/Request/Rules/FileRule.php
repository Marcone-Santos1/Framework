<?php

namespace MiniRestFramework\Http\Request\Rules;

use MiniRestFramework\Http\Request\RequestValidation\ValidationRule;

class FileRule implements ValidationRule
{

    public function passes($value, $params = []): bool
    {
        if (!is_uploaded_file($value['tmp_name'])) {
            return false;
        }

        $allowedTypes = $params;
        $fileType = pathinfo($value['name'], PATHINFO_EXTENSION);
        return in_array($fileType, $allowedTypes);
    }

    public function errorMessage($field, $params = []): string
    {
        return "O campo $field deve ser um arquivo do tipo " . implode(', ', $params);
    }
}
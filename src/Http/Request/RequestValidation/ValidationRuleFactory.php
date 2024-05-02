<?php

namespace MiniRest\Framework\Http\Request\RequestValidation;

use Exception;
use MiniRest\Framework\Exceptions\RuleNotFound;

class ValidationRuleFactory
{
    /**
     * @throws Exception
     */
    public static function createRule($ruleName): ValidationRule
    {
        $className =  "MiniRest\Framework\\Http\Request\\Rules\\" .
            ucfirst($ruleName) . 'Rule';

        if (class_exists($className)) {
            return new $className();
        }

        throw new RuleNotFound("Regra de validação {$ruleName} não encontrada.");
    }
}
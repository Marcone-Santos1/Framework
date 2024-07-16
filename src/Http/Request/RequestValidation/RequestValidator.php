<?php

namespace MiniRestFramework\Http\Request\RequestValidation;

use \Exception;
use MiniRestFramework\Helpers\StatusCode\StatusCode;
use MiniRestFramework\Http\Request\Request;
use MiniRestFramework\Http\Response\Response;
use function Symfony\Component\Translation\t;

class RequestValidator
{
    protected $rules = [];
    protected $errorMessages = [];

    protected string $objectType;

    /**
     * @throws \Exception
     */
    public function rules(array $rules)
    {
        $this->rules = [];
        foreach ($rules as $field => $rule) {
            $this->rules[$field] = [];

            foreach (explode('|', $rule) as $rawRule) {
                [$ruleName, $ruleParams] = $this->parseRule($rawRule);
                $validationRule = ValidationRuleFactory::createRule($ruleName);
                $this->rules[$field][] = [
                    'rule' => $validationRule,
                    'params' => $ruleParams,
                ];
            }
        }
        return $this;
    }

    private function parseRule($rawRule): array
    {
        $parts = explode(':', $rawRule);
        $ruleName = $parts[0];
        $ruleParams = isset($parts[1]) ? explode(',', $parts[1]) : [];

        return [$ruleName, $ruleParams];
    }

    public function validate(string $objectType = 'json') : RequestValidator
    {
        $this->objectType = $objectType;

        $data = (new Request())->all()->get($objectType);

        $this->errorMessages = [];
        foreach ($this->rules as $field => $rules) {

            if (!isset($data[$field])) {
                $this->errorMessages[$field][] = "Parametro \"$field\" não encontrada.";
                continue;
            }

            foreach ($rules as $rule) {
                if (!$rule['rule']->passes($data[$field], $rule['params'])) {
                    $errorMessage = $rule['rule']->errorMessage($field, $rule['params']);
                    $this->addError($field, $errorMessage);
                }
            }
        }

        return $this;
    }

    public function addError(string $field, string $errorMessage): void
    {
        $this->errorMessages[$field][] = $errorMessage;
    }

    public function errors(): array
    {
        if (count($this->errorMessages) <= 0) return [];

        $errors = [];
        foreach ($this->errorMessages as $item){
            $errors[] = $item[0];
        }

        return $errors;
    }

    public function fails(): bool
    {
        $this->validate($this->objectType ?? 'json');
        return count($this->errors()) > 0;
    }
}
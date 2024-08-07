<?php

namespace MiniRestFramework\Http\Request;

use MiniRestFramework\Http\Request\RequestValidation\RequestValidator;

class Request extends RequestValidator
{

    private array $params = [];
    private array $requestData = [];
    private array $routeParams = [];

    public function __construct()
    {
    }

    public function setRouteParams(array $params): void
    {
        $this->routeParams = $params;
    }

    public function setRquestParams(): void
    {
        $this->requestData = $this->getJsonData();
    }

    public function getRouteParams(): array
    {
        return $this->routeParams;
    }

    public function __get($name)
    {
        if (!isset($this->routeParams[$name]) && !isset($this->requestData[$name])) return null;

        return $this->routeParams[$name] ?? $this->requestData[$name];
    }

    public function set(string $key, $value): void {
        $this->params[$key] = $value;
    }

    public function get(string $key, $default = null) {
        return $this->params[$key] ?? $this->requestData[$key] ?? $default;
    }

    public function post($key, $default = null) {
        if (isset($_POST[$key])) {
            return $_POST[$key];
        }

        return $default;
    }

    public function files($key, $default = null) {
        if (isset($_FILES[$key])) {
            return $_FILES[$key];
        }

        return $default;
    }

    public function json($key, $default = null) {
        $data = $this->getJsonData();

        if (isset($data[$key])) {
            return $data[$key];
        }

        return $default;
    }

    public function all(): Collection
    {
        return new Collection([
            'get' => $_GET,
            'post' => $_POST,
            'files' => $_FILES,
            'json' => $this->getJsonData(),
        ]);
    }

    protected function getJsonData() {
        $jsonData = file_get_contents('php://input');
        return json_decode($jsonData, true);
    }

    public function headers(string $headerName)
    {
        $headers = $this->getAllHeaders();

        if (isset($headers[$headerName])) {
            return $headers[$headerName];
        }

        return null;
    }

    private function getAllHeaders(): array
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) === 'HTTP_') {
                $headerName = str_replace('_', ' ', substr($key, 5));
                $headerName = ucwords(strtolower($headerName));
                $headerName = str_replace(' ', '-', $headerName);
                $headers[$headerName] = $value;
            }
        }

        return $headers;
    }

    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getUri()
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '';
    }
}
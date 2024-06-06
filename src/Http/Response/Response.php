<?php

namespace MiniRestFramework\Http\Response;

use MiniRestFramework\Exceptions\InvalidContentTypeException;
use MiniRestFramework\Helpers\StatusCode\StatusCode;

class Response
{
    private int $status;
    private array $headers = [];
    private mixed $body;

    public function __construct(mixed $body = '', int|StatusCode $status = StatusCode::OK, array $headers = [])
    {
        $this->body = $body;
        $this->status = $status;
        $this->headers = $headers;
    }

    public static function json(mixed $data, int|StatusCode $status = StatusCode::OK, array $headers = []): self
    {
        $headers[] = 'Content-Type: application/json';
        return new self(json_encode($data), $status, $headers);
    }

    /**
     * @throws InvalidContentTypeException
     */
    public static function anyType(mixed $data, string $type, int|StatusCode $status = StatusCode::OK, array $headers = []): self
    {
        if (strlen($type) <= 0) throw new InvalidContentTypeException();

        $headers[] = "Content-Type: $type";
        return new self($data, $status, $headers);
    }

    public static function notFound(array $headers = []): self
    {
        return self::json(['error' => 'Route not found'], StatusCode::NOT_FOUND, $headers);
    }

    public static function html(string $html, int|StatusCode $status = StatusCode::OK, array $headers = []): self
    {
        $headers[] = 'Content-Type: text/html';
        return new self($html, $status, $headers);
    }

    public static function text(string $text, int|StatusCode $status = StatusCode::OK, array $headers = []): self
    {
        $headers[] = 'Content-Type: text/plain';
        return new self($text, $status, $headers);
    }

    public function setStatus(int|StatusCode $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function addHeader(string $header): self
    {
        $this->headers[] = $header;
        return $this;
    }

    public function removeHeader(string $header): self
    {
        $this->headers = array_filter($this->headers, fn($h) => $h !== $header);
        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function send(): void
    {
        http_response_code($this->status);

        foreach ($this->headers as $header) {
            header($header);
        }

        echo $this->body;
    }
}

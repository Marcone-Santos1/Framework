<?php

namespace MiniRestFramework\Http\Response;
use MiniRestFramework\Exceptions\InvalidContentTypeException;
use MiniRestFramework\Exceptions\InvalidJsonResponseException;
use MiniRestFramework\Helpers\StatusCode\StatusCode;

class Response {
    public static function json(mixed $data, int | StatusCode $status = StatusCode::OK): string|false
    {
        header('Content-Type: application/json');
        http_response_code($status);
        return json_encode($data);
    }

    /**
     * @throws InvalidContentTypeException
     */
    public static function anyType(mixed $data, string $type, int | StatusCode $status = StatusCode::OK): mixed
    {
        if (strlen($type) <= 0) throw new InvalidContentTypeException();

        header("Content-Type: $type");
        http_response_code($status);
        return $data;
    }


    public static function notFound(): string|false
    {
        return self::json(['error' => 'Route not found'], StatusCode::NOT_FOUND);
    }
}
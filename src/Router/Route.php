<?php

namespace MiniRestFramework\Router;

class Route
{
    private const METHOD_GET = 'GET';
    private const METHOD_POST = 'POST';
    private const METHOD_PUT = 'PUT';
    private const METHOD_PATCH = 'PATCH';
    private const METHOD_DELETE = 'DELETE';

    private static mixed $prefix = '';


    public static function get($uri, $action, $middleware = []){
        Router::add(self::METHOD_GET, $uri, $action, self::$prefix, $middleware);
    }

    public static function post($uri, $action, $middleware = []) {
        Router::add(self::METHOD_POST, $uri, $action, self::$prefix, $middleware);
    }

    public static function put($uri, $action, $middleware = []) {
        Router::add(self::METHOD_PUT, $uri, $action, self::$prefix, $middleware);
    }

    public static function delete($uri, $action, $middleware = []) {
        Router::add(self::METHOD_DELETE, $uri, $action, self::$prefix, $middleware);
    }

    public static function patch($uri, $action, $middleware = []) {
        Router::add(self::METHOD_PATCH, $uri, $action, self::$prefix, $middleware);
    }

    public static function prefix($prefix): Route
    {
        self::$prefix = $prefix;
        return new Route();
    }

    public function group($middlewares, $callback): void
    {
        $groupMiddlewares = is_array($middlewares) ? $middlewares : [$middlewares];
        Router::$groupMiddlewares = array_merge(Router::$groupMiddlewares, $groupMiddlewares);
        $callback();
        Router::$groupMiddlewares = array_diff(Router::$groupMiddlewares, $groupMiddlewares);
        self::$prefix = '';
    }
}
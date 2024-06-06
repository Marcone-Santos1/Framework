<?php

namespace MiniRestFramework\Router;

use MiniRestFramework\DI\Container;
use MiniRestFramework\Http\Middlewares\MiddlewarePipeline;
use MiniRestFramework\Http\Request\Request;
use MiniRestFramework\Http\Response\Response;

class ActionDispatcher {
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function execute(Request $request, $action, $params, $middlewares = null): Response
    {
        if (is_callable($action)) {
            return $this->handleClosure($request, $action, $middlewares);
        }

        [$controllerClass, $method] = $action;

        $controller = $this->container->make($controllerClass);

        $reflectionMethod = new \ReflectionMethod($controllerClass, $method);
        $methodParameters = $reflectionMethod->getParameters();
        $resolvedParameters = [];

        foreach ($methodParameters as $param) {
            $paramType = $param->getType();
            if ($paramType && $paramType->isBuiltin()) {
                $paramName = $param->getName();
                $resolvedParameters[] = $params[$paramName] ?? null;
            } elseif ($paramType) {
                $resolvedParameters[] = $this->container->make($paramType->getName());
            }
        }

        if (!$middlewares) {
            $response = $reflectionMethod->invokeArgs($controller, $resolvedParameters);
            return $response instanceof Response ? $response : Response::json($response);
        }

        $middlewarePipeline = new MiddlewarePipeline();
        $middlewarePipeline->send($request)->through($middlewares);
        return $middlewarePipeline->then(function ($passable) use ($controller, $reflectionMethod, $resolvedParameters) {
            $response = $reflectionMethod->invokeArgs($controller, $resolvedParameters);
            if (!$response instanceof Response) {
                $response = Response::json($response);
            }
            return $response;
        });
    }

    protected function handleClosure(Request $request, callable $closure, $middlewares = null): Response
    {
        if ($middlewares) {
            $middlewarePipeline = new MiddlewarePipeline();
            $middlewarePipeline->send($request)->through($middlewares);
            return $middlewarePipeline->then(function ($passable) use ($request, $closure) {
                $params = array_merge($request->getRouteParams(), (array)$request->all());
                $response = call_user_func_array($closure, [$request, ...array_values($params)]);
                return $response instanceof Response ? $response : Response::json($response);
            });
        }
        $params = array_merge($request->getRouteParams(), (array)$request->all());
        $response = call_user_func_array($closure, [$request, ...array_values($params)]);
        return $response instanceof Response ? $response : Response::json($response);
    }
}

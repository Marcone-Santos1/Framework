<?php

namespace MiniRestFramework\Router;

use MiniRestFramework\DI\Container;
use MiniRestFramework\Http\Middlewares\MiddlewarePipeline;
use MiniRestFramework\Http\Request\Request;
use MiniRestFramework\Http\Response\Response;

class Router {
    public static array $routes = [];
    public static array $groupMiddlewares = [];
    public static Container $container;

    public function __construct(Container $container)
    {
        self::$container = $container;
    }

    public static function add($method, $route, $action, $prefix = '', $middlewares = []): void
    {
        $pattern = '#^' . preg_replace('/\{([a-zA-Z0-9_]+)\??\}/', '(?P<$1>[^/]+)?', $prefix . $route) . '$#';

        $mergedMiddlewares = array_merge(self::$groupMiddlewares, $middlewares);

        self::$routes[] = [
            'method' => $method,
            'route' => $pattern, // Padrão regex com parâmetros capturados
            'action' => $action,
            'middlewares' => $mergedMiddlewares,
        ];
    }

    /**
     * @throws \ReflectionException
     */
    public static function dispatch(Request $request): Response|null
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];


        if (in_array(env('ENVIRONMENT'), ['development', 'local'])) {
            if ($_SERVER['HTTPS'] !== 'on') {
                $url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                header('Location: ' . $url, true, 301);
                exit();
            }
        }

        if ($method == 'OPTIONS') {
            http_response_code(200);
            exit();
        }

        $matches = [];

        foreach (self::$routes as $route) {
            if ($route['method'] === $method && preg_match($route['route'], $uri, $matches)) {
                array_shift($matches);

                // Adicionando parâmetros da rota ao objeto Request
                foreach ($matches as $key => $value) {
                    if (!is_int($key)) {
                        $request->set($key, $value);
                    }
                }

                $request->setRouteParams($matches);


                $middlewareList = [];
                if (count($route['middlewares']) > 0) {
                    foreach ($route['middlewares'] as $middleware) {
                        $middlewareList[] = new $middleware();
                    }
                }

                return self::executeAction($request , $route['action'], $matches, $middlewareList);
            }
        }

        return Response::notFound();
    }

    /**
     * @throws \ReflectionException
     */
    protected static function executeAction(Request $request, $action, $params, $middlewares = null): Response
    {
        [$controllerClass, $method] = $action;

        $controller = self::$container->make($controllerClass);

        $reflectionMethod = new \ReflectionMethod($controllerClass, $method);
        $methodParameters = $reflectionMethod->getParameters();
        $resolvedParameters = [];

        foreach ($methodParameters as $param) {
            $paramType = $param->getType();
            if ($paramType && $paramType->isBuiltin()) {
                $paramName = $param->getName();
                $resolvedParameters[] = $params[$paramName] ?? null;
            } elseif ($paramType) {
                $resolvedParameters[] = self::$container->make($paramType->getName());
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
}

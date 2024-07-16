<?php

namespace MiniRestFramework\Router;

use MiniRestFramework\Http\Request\Request;
use MiniRestFramework\Http\Response\Response;

class Router {
    public static array $routes = [];
    public static array $groupMiddlewares = [];

    private ActionDispatcher $actionDispatcher;

    public function __construct(ActionDispatcher $actionDispatcher)
    {
        $this->actionDispatcher = $actionDispatcher;
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
    public function dispatch(Request $request): Response|null
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
                $request->setRquestParams();

                $middlewareList = [];
                if (count($route['middlewares']) > 0) {
                    foreach ($route['middlewares'] as $middleware) {
                        $middlewareList[] = new $middleware();
                    }
                }

                return $this->actionDispatcher->execute($request, $route['action'], $matches, $middlewareList);
            }
        }

        return Response::notFound();
    }

}

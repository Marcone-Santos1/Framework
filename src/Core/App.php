<?php

namespace MiniRestFramework\Core;
use MiniRestFramework\DI\Container;
use MiniRestFramework\Http\Request\Request;
use MiniRestFramework\Router\ActionDispatcher;
use MiniRestFramework\Router\Router;

class App {
    private Container $container;

    public function __construct()
    {
        $this->container = new Container();
    }


    /**
     * @throws \ReflectionException
     */
    public function run()
    {

        $routersPath = dirname(__DIR__, 5) . '/routers/';

        // Obter todos os arquivos de rota da pasta routers
        $routerFiles = glob($routersPath . '*.php');

        // Incluir cada arquivo de rota
        foreach ($routerFiles as $file) {
            require_once $file;
        }
        $actionDispatcher = new ActionDispatcher($this->container);

        $router = new Router($actionDispatcher);
        $router->dispatch(new Request())->send();
    }
}

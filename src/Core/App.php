<?php

namespace MiniRestFramework\Core;
use MiniRestFramework\DI\Container;
use MiniRestFramework\Http\Request\Request;
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
    public function run(): false|string|null
    {

        $routersPath = dirname(__DIR__, 5) . '/routers/';

        // Obter todos os arquivos de rota da pasta routers
        $routerFiles = glob($routersPath . '*.php');

        // Incluir cada arquivo de rota
        foreach ($routerFiles as $file) {
            require_once $file;
        }

        $router = new Router($this->container);
        return $router::dispatch(new Request());
    }
}

<?php

namespace MiniRestFramework\Core;
use MiniRestFramework\Http\Request\Request;
use MiniRestFramework\Router\Router;

class App {
    /**
     * @throws \ReflectionException
     */
    public function run(): false|string|null
    {

        $routersPath = dirname(__DIR__, 3) . '/routers/*.php';


        // Obter todos os arquivos de rota da pasta routers
        $routerFiles = glob($routersPath . '*.php');

        // Incluir cada arquivo de rota
        foreach ($routerFiles as $file) {
            require_once $file;
        }

        $routerFiles = dirname(__DIR__, 2) . '/routers/api.php';

        require_once $routerFiles;

        return Router::dispatch(new Request());
    }
}
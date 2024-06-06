<?php

require_once '../vendor/autoload.php';

use MiniRestFramework\Core\App;
use MiniRestFramework\Router\Router;

$app = new App();

Router::post('/test', [\MiniRestFramework\Tests\Objects\ExampleController::class, 'handleRequest']);
Router::post('/testWithoutDI', [\MiniRestFramework\Tests\Objects\ExampleController::class, 'testWithoutDI']);
Router::post('/sayHello', [\MiniRestFramework\Tests\Objects\ExampleController::class, 'testMethod']);
Router::post('/testParam/{id}', [\MiniRestFramework\Tests\Objects\ExampleController::class, 'testParam']);
Router::post('/testParam2/{id}/{isReal}', [\MiniRestFramework\Tests\Objects\ExampleController::class, 'testParam']);

$app->run();
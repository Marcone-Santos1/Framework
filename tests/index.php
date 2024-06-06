<?php

require_once '../vendor/autoload.php';

use MiniRestFramework\Core\App;
use MiniRestFramework\Router\Route;

$app = new App();

Route::post('/test', [\MiniRestFramework\Tests\Objects\ExampleController::class, 'handleRequest']);
Route::post('/testWithoutDI', [\MiniRestFramework\Tests\Objects\ExampleController::class, 'testWithoutDI']);
Route::post('/sayHello', [\MiniRestFramework\Tests\Objects\ExampleController::class, 'testMethod']);
Route::post('/testParam/{id}', [\MiniRestFramework\Tests\Objects\ExampleController::class, 'testParam']);
Route::post('/testParam2/{id}/{isReal}', [\MiniRestFramework\Tests\Objects\ExampleController::class, 'testParam'], [\MiniRestFramework\Tests\Objects\ExampleMiddleware::class]);

Route::prefix('/api')->group([], function () {
    Route::post('/test', [\MiniRestFramework\Tests\Objects\ExampleController::class, 'handleRequest']);
});

Route::prefix('/api2')->group([\MiniRestFramework\Tests\Objects\ExampleMiddleware::class], function () {
    Route::post('/test', [\MiniRestFramework\Tests\Objects\ExampleController::class, 'handleRequest']);
});

//var_dump(\MiniRestFramework\Router\Router::$routes);

$app->run();
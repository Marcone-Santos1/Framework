<?php

require_once '../vendor/autoload.php';

use MiniRestFramework\Core\App;
use MiniRestFramework\Http\Request\Request;
use MiniRestFramework\Http\Response\Response;
use MiniRestFramework\Router\Route;
use MiniRestFramework\Tests\Objects\ExampleController;
use MiniRestFramework\Tests\Objects\ExampleMiddleware;
use MiniRestFramework\Tests\Objects\ExampleMiddleware2;

$app = new App();

Route::prefix('/api2')->group([ExampleMiddleware::class], function () {
    Route::post('/test', [ExampleController::class, 'handleRequest']);
});

Route::post('/test', [ExampleController::class, 'handleRequest']);

Route::post('/testClosures/{id}/{isReal}', function (Request $request, $id, $isReal) {
    return Response::json("Received ID: $id");
}, [ExampleMiddleware::class, ExampleMiddleware2::class]);


Route::get('/test/xss', function (Request $request) {
    return Response::html($request->all()->get('get')['data']);
});

Route::post('/testWithoutDI', [ExampleController::class, 'testWithoutDI']);
Route::post('/sayHello', [ExampleController::class, 'testMethod']);
Route::post('/testParam/{id}', [ExampleController::class, 'testParam']);
Route::post('/testParam2/{id}/{isReal}', [ExampleController::class, 'testParam'], [ExampleMiddleware::class]);

Route::prefix('/api')->group([], function () {
    Route::post('/test', [ExampleController::class, 'handleRequest']);
});

Route::prefix('/test')->group([], function () {
    Route::post('/test1', [ExampleController::class, 'handleRequest']);
});

//var_dump(\MiniRestFramework\Router\Router::$routes);

$app->run();
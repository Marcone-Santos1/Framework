<?php

require_once '../../vendor/autoload.php';

use MiniRestFramework\Core\App;
use MiniRestFramework\Router\Router;
use MiniRestFramework\Tests\Objects\ExampleController;

$app = new App();

Router::get('/test', [ExampleController::class, 'handleRequest']);


$app->run();